<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if user has farmer profile
        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }
        
        $farmer = $user->farmer;
        $products = Product::where('farmer_id', $farmer->id)->paginate(10);
        
        return view('farmers.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if user has farmer profile
        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }
        
        return view('farmers.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if user has farmer profile
        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }
        
        $request->validate([
            'egg_type' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'harvest_date' => 'required|date',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'required|array|min:1',
            'sizes.*.name' => 'required|string|in:small,medium,large,extra_large',
            'sizes.*.tray_count' => 'required|integer|min:1',
            'sizes.*.price_per_tray' => 'required|numeric|min:0',
        ]);

        $farmer = $user->farmer;

        // Set product name based on egg type
        $eggTypeName = $request->input('egg_type');
        $productNameMap = [
            'chicken' => 'Chicken Eggs',
            'duck' => 'Duck Eggs',
            'quail' => 'Quail Eggs',
            'native_chicken' => 'Native Chicken Eggs',
            'brown' => 'Brown Eggs',
            'white' => 'White Eggs'
        ];
        $productName = $productNameMap[$eggTypeName] ?? 'Eggs';
        
        $product = new Product($request->except(['images', 'sizes']));
        $product->farmer_id = $farmer->id;
        
        $product->save();
        
        // Create size records
        $sizesData = $request->input('sizes');
        $totalQuantity = 0;
        $totalPrice = 0;
        
        foreach ($sizesData as $sizeData) {
            $trayCount = $sizeData['tray_count'];
            $pricePerTray = $sizeData['price_per_tray'];
            $totalSizePrice = $trayCount * $pricePerTray;
            
            Size::create([
                'farmer_id' => $farmer->id,
                'product_id' => $product->id,
                'egg_type' => $request->input('egg_type'),
                'size_name' => $sizeData['name'],
                'tray_count' => $trayCount,
                'price_per_tray' => $pricePerTray,
                'total_price' => $totalSizePrice
            ]);
            
            $totalQuantity += $trayCount;
            $totalPrice += $totalSizePrice;
        }
        
        // Update product with calculated totals
        $product->quantity = $totalQuantity;
        $product->price = $totalPrice;
        $product->save();
        
        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $index => $image) {
                if ($image && $image->isValid()) {
                    $imagePath = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => ($index === 0) // First image is primary
                    ]);
                    
                    // Set the primary image path on the product
                    if ($index === 0) {
                        $product->image = $imagePath;
                        $product->save();
                    }
                }
            }
        }

        return redirect()->route('products.index')
                         ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if user has farmer profile
        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }
        
        // Ensure farmer can only view their own products
        if ($product->farmer_id != $user->farmer->id) {
            abort(403);
        }
        
        // Load product images and sizes
        $product->load(['images', 'sizes']);
        
        // Get unread message count for the farmer
        $unreadMessageCount = 0;
        if ($user->farmer) {
            $unreadMessageCount = \App\Models\Message::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();
        }
        
        return view('farmers.products.show', compact('product', 'unreadMessageCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if user has farmer profile
        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }
        
        // Ensure farmer can only edit their own products
        if ($product->farmer_id != $user->farmer->id) {
            abort(403);
        }
        
        // Load product images and sizes
        $product->load(['images', 'sizes']);
        
        return view('farmers.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if user has farmer profile
        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }
        
        // Ensure farmer can only update their own products
        if ($product->farmer_id != $user->farmer->id) {
            abort(403);
        }
        
        $request->validate([
            'egg_type' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'harvest_date' => 'required|date',
            'address' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array|max:10',
            'sizes' => 'required|array|min:1',
            'sizes.*.name' => 'required|string|in:small,medium,large,extra_large',
            'sizes.*.tray_count' => 'required|integer|min:1',
            'sizes.*.price_per_tray' => 'required|numeric|min:0',
        ]);

        // Set product name based on egg type
        $eggTypeName = $request->input('egg_type');
        $productNameMap = [
            'chicken' => 'Chicken Eggs',
            'duck' => 'Duck Eggs',
            'quail' => 'Quail Eggs',
            'native_chicken' => 'Native Chicken Eggs',
            'brown' => 'Brown Eggs',
            'white' => 'White Eggs'
        ];
        $productName = $productNameMap[$eggTypeName] ?? 'Eggs';
        
        $product->fill($request->except(['images', 'sizes']));
        
        // Handle size updates
        // Delete existing sizes
        $product->sizes()->delete();
        
        // Create new size records
        $sizesData = $request->input('sizes');
        $totalQuantity = 0;
        $totalPrice = 0;
        
        foreach ($sizesData as $sizeData) {
            $trayCount = $sizeData['tray_count'];
            $pricePerTray = $sizeData['price_per_tray'];
            $totalSizePrice = $trayCount * $pricePerTray;
            
            Size::create([
                'farmer_id' => $user->farmer->id,
                'product_id' => $product->id,
                'egg_type' => $request->input('egg_type'),
                'size_name' => $sizeData['name'],
                'tray_count' => $trayCount,
                'price_per_tray' => $pricePerTray,
                'total_price' => $totalSizePrice
            ]);
            
            $totalQuantity += $trayCount;
            $totalPrice += $totalSizePrice;
        }
        
        // Update product with calculated totals
        $product->quantity = $totalQuantity;
        $product->price = $totalPrice;
        
        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            
            // Delete old images if any new images are uploaded
            if (count($images) > 0) {
                // Delete old images from storage
                foreach ($product->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage->image_path);
                }
                
                // Delete old images records
                $product->images()->delete();
                
                // Upload new images
                foreach ($images as $index => $image) {
                    if ($image && $image->isValid()) {
                        $imagePath = $image->store('products', 'public');
                        
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'is_primary' => ($index === 0) // First image is primary
                        ]);
                        
                        // Set the primary image path on the product
                        if ($index === 0) {
                            $product->image = $imagePath;
                        }
                    }
                }
            }
        }
        
        $product->save();

        return redirect()->route('products.index')
                         ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if user has farmer profile
        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }
        
        // Ensure farmer can only delete their own products
        if ($product->farmer_id != $user->farmer->id) {
            abort(403);
        }
        
        // Delete associated sizes
        $product->sizes()->delete();
        
        // Delete associated images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        
        // Delete the product
        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Product deleted successfully.');
    }

    /**
     * Update product status (Available / Pending / Sold Out)
     */
    public function updateStatus(Request $request, Product $product)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if user has farmer profile
        $user = Auth::user();
        if (!$user->farmer) {
            abort(403, 'Access denied. Farmer profile required.');
        }
        
        // Ensure farmer can only update their own products
        if ($product->farmer_id != $user->farmer->id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'status' => 'required|in:Available,Pending,Sold Out'
        ]);
        
        $product->status = $validated['status'];
        $product->save();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product status updated successfully.',
                'status' => $product->status,
            ]);
        }
        
        return redirect()->back()->with('success', 'Product status updated successfully.');
    }
}