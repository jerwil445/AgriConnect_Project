<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\RemainingInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }

        $search = trim((string) $request->input('search', ''));
        $statusFilter = $request->input('status');
        $sort = $request->input('sort', 'latest');
        $perPage = (int) $request->input('per_page', 10);

        if (!in_array($perPage, [10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        if (!in_array($statusFilter, ['Available', 'Pending', 'Sold Out'], true)) {
            $statusFilter = '';
        }

        if (!in_array($sort, ['latest', 'oldest', 'name_asc', 'price_high', 'price_low'], true)) {
            $sort = 'latest';
        }

        $productsQuery = Product::where('farmer_id', $user->farmer->id)
            ->with('remainingInventory');

        if ($search !== '') {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('product_name', 'ILIKE', '%' . $search . '%')
                    ->orWhere('variety_size', 'ILIKE', '%' . $search . '%')
                    ->orWhere('description', 'ILIKE', '%' . $search . '%')
                    ->orWhere('unit', 'ILIKE', '%' . $search . '%');
            });
        }

        if ($statusFilter !== '') {
            $productsQuery->where('status', $statusFilter);
        }

        match ($sort) {
            'oldest' => $productsQuery->orderBy('created_at'),
            'name_asc' => $productsQuery->orderBy('product_name'),
            'price_high' => $productsQuery->orderByDesc('price'),
            'price_low' => $productsQuery->orderBy('price'),
            default => $productsQuery->orderByDesc('created_at'),
        };

        $products = $productsQuery
            ->paginate($perPage)
            ->withQueryString();

        return view('farmers.products.index', compact(
            'products',
            'search',
            'statusFilter',
            'sort',
            'perPage'
        ));
    }

    /**
     * Display all orders for a specific product.
     */
    public function orders(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }

        // Ensure the product belongs to this farmer
        if ($product->farmer_id != $user->farmer->id) {
            abort(403);
        }

        // Fetch all transactions (orders) for this product
        $orders = \App\Models\Transaction::where('product_id', $product->id)
            ->with('buyer')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('farmers.products.orders', compact('product', 'orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }

        return view('farmers.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }

        $validated = $this->validateProduct($request);
        $validated['farmer_id'] = $user->farmer->id;
        $validated['total_amount'] = $this->calculateTotalAmount($validated['quantity'], $validated['price']);
        $validated['status'] = $validated['status'] ?? 'Available';

        $product = Product::create(collect($validated)->except(['images'])->all());

        $this->syncImages($request, $product);
        $this->syncRemainingInventory($product);

        // Run matching engine to automatically find buyers with demands matching this new product
        app(\App\Http\Controllers\DemandMatchingController::class)->runMatchingEngineForProduct($product);

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $user = Auth::user();

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }

        if ($product->farmer_id != $user->farmer->id) {
            abort(403);
        }

        $product->load(['images', 'remainingInventory']);
        $this->syncRemainingInventory($product);
        $product->refresh()->load(['images', 'remainingInventory']);

        $unreadMessageCount = \App\Models\Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('farmers.products.show', compact('product', 'unreadMessageCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }

        if ($product->farmer_id != $user->farmer->id) {
            abort(403);
        }

        $product->load(['images', 'remainingInventory']);

        return view('farmers.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }

        if ($product->farmer_id != $user->farmer->id) {
            abort(403);
        }

        $validated = $this->validateProduct($request);
        $validated['total_amount'] = $this->calculateTotalAmount($validated['quantity'], $validated['price']);

        $product->fill(collect($validated)->except(['images'])->all());
        $product->save();

        $this->syncImages($request, $product, true);
        $this->syncRemainingInventory($product);

        // Run matching engine in case updating properties makes it match new demands
        app(\App\Http\Controllers\DemandMatchingController::class)->runMatchingEngineForProduct($product);

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }

        if ($product->farmer_id != $user->farmer->id) {
            abort(403);
        }

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Update product status.
     */
    public function updateStatus(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }

        if ($product->farmer_id != $user->farmer->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Available,Pending,Sold Out',
        ]);

        $product->status = $request->input('status');
        $product->save();

        $inventory = $this->syncRemainingInventory($product);

        if ($product->status === 'Available') {
            app(\App\Http\Controllers\DemandMatchingController::class)->runMatchingEngineForProduct($product);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product status updated successfully.',
            'remaining_quantity' => $inventory->remaining_quantity,
        ]);
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'product_name' => 'required|string|max:255',
            'variety_size' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'harvest_date' => 'required|date',
            'status' => 'nullable|in:Available,Pending,Sold Out',
            'purok_street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    private function syncImages(Request $request, Product $product, bool $replaceExisting = false): void
    {
        if (!$request->hasFile('images')) {
            return;
        }

        $images = array_filter($request->file('images', []));
        if (empty($images)) {
            return;
        }

        if ($replaceExisting) {
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
            }

            $product->images()->delete();
        }

        foreach ($images as $index => $image) {
            if (!$image || !$image->isValid()) {
                continue;
            }

            $imagePath = $image->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $imagePath,
                'is_primary' => ($index === 0),
            ]);

            if ($index === 0) {
                $product->image = $imagePath;
                $product->save();
            }
        }
    }

    private function syncRemainingInventory(Product $product): RemainingInventory
    {
        $product->loadMissing('remainingInventory');
        $inventory = $product->remainingInventory;

        if (!$inventory) {
            return RemainingInventory::create([
                'product_id' => $product->id,
                'original_quantity' => (int) $product->quantity,
                'original_price' => $this->calculateTotalAmount($product->quantity, $product->price),
                'original_total_trays' => (int) $product->quantity,
                'remaining_quantity' => (int) $product->quantity,
                'remaining_price' => $this->calculateTotalAmount($product->quantity, $product->price),
                'remaining_total_trays' => (int) $product->quantity,
                'last_updated' => now(),
            ]);
        }

        $oldOriginal = (int) $inventory->original_quantity;
        $newOriginal = (int) $product->quantity;
        $currentRemaining = (int) $inventory->remaining_quantity;

        // Logic 1: Handle Quantity Delta (Add stock)
        $diff = $newOriginal - $oldOriginal;
        $newRemaining = $currentRemaining + $diff;

        // Logic 2: Handle explicit "Available" toggle for sold-out items (Refill)
        if ($product->status === 'Available' && $currentRemaining <= 0) {
            // If they made it available but didn't increase total, assume a refill to current total
            if ($diff <= 0) {
                $newRemaining = $newOriginal;
            }
        }

        // Logic 3: Handle "Sold Out" status override
        if ($product->status === 'Sold Out') {
            $newRemaining = 0;
        }

        // Bounds checking
        $newRemaining = max(0, min($newRemaining, $newOriginal));

        $inventory->update([
            'original_quantity' => $newOriginal,
            'original_price' => $this->calculateTotalAmount($newOriginal, $product->price),
            'original_total_trays' => $newOriginal,
            'remaining_quantity' => $newRemaining,
            'remaining_price' => $this->calculateTotalAmount($newRemaining, $product->price),
            'remaining_total_trays' => $newRemaining,
            'last_updated' => now(),
        ]);

        // Auto-correct status if quantity is 0
        if ($newRemaining <= 0 && $product->status !== 'Sold Out') {
            $product->status = 'Sold Out';
            $product->save();
        }

        return $inventory->fresh();
    }

    private function calculateTotalAmount($quantity, $price): string
    {
        return number_format(((float) $quantity) * ((float) $price), 2, '.', '');
    }
}
