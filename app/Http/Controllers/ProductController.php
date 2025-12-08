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
            'purok_street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'required|array|min:1',
            'sizes.*.name' => 'required|string|in:small,medium,large,extra_large,jumbo',
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
        
        // Load product images, sizes, and remaining inventory
        $product->load(['images', 'sizes', 'remainingInventory']);
        
        // If no remaining inventory record exists, create one with original values
        if (!$product->remainingInventory) {
            $perSizeRemaining = [];
            foreach ($product->sizes as $size) {
                $perSizeRemaining[] = [
                    'size_id' => $size->id,
                    'size_name' => $size->size_name,
                    'original_tray_count' => $size->tray_count,
                    'remaining_tray_count' => $size->tray_count,
                    'original_price_per_tray' => $size->price_per_tray,
                    'remaining_price_per_tray' => $size->price_per_tray,
                    'original_total_price' => $size->total_price,
                    'remaining_total_price' => $size->total_price
                ];
            }
            
            $remainingInventory = \App\Models\RemainingInventory::create([
                'product_id' => $product->id,
                'original_quantity' => $product->quantity,
                'original_price' => $product->price,
                'original_total_trays' => $product->sizes->sum('tray_count'),
                'remaining_quantity' => $product->quantity,
                'remaining_price' => $product->price,
                'remaining_total_trays' => $product->sizes->sum('tray_count'),
                'per_size_remaining' => $perSizeRemaining,
                'last_updated' => now()
            ]);
            
            $product->setRelation('remainingInventory', $remainingInventory);
        }
        
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
            'purok_street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array|max:10',
            'sizes' => 'required|array|min:1',
            'sizes.*.name' => 'required|string|in:small,medium,large,extra_large,jumbo',
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
        
        // Update remaining inventory record with new original values if it exists
        if ($product->remainingInventory) {
            $perSizeRemaining = [];
            
            // If product is marked as sold out, keep remaining values at zero
            if ($product->status === 'Sold Out') {
                $newRemainingQuantity = 0;
            } else {
                // Calculate the difference between original and remaining values to maintain sold quantities
                $soldQuantity = $product->remainingInventory->original_quantity - $product->remainingInventory->remaining_quantity;
                // Calculate new remaining quantity based on sold quantity
                // Ensure we don't go below zero
                $newRemainingQuantity = max(0, $product->quantity - $soldQuantity);
            }
            
            foreach ($product->sizes as $size) {
                // Find existing size data
                $existingSize = null;
                foreach ($product->remainingInventory->per_size_remaining ?? [] as $existing) {
                    if ($existing['size_id'] == $size->id) {
                        $existingSize = $existing;
                        break;
                    }
                }
                
                // Calculate remaining values based on what has been sold
                $originalTrayCount = $size->tray_count;
                $originalPricePerTray = $size->price_per_tray;
                $originalTotalPrice = $size->total_price;
                
                // If product is marked as sold out, keep remaining values at zero
                if ($product->status === 'Sold Out') {
                    $remainingTrayCount = 0;
                    $remainingTotalPrice = 0;
                } else {
                    // If we have existing data, calculate remaining based on what was sold
                    if ($existingSize) {
                        $soldTrayCount = $existingSize['original_tray_count'] - $existingSize['remaining_tray_count'];
                        $remainingTrayCount = max(0, $originalTrayCount - $soldTrayCount);
                        $remainingTotalPrice = $remainingTrayCount * $originalPricePerTray;
                    } else {
                        // If no existing data, use original values
                        $remainingTrayCount = $originalTrayCount;
                        $remainingTotalPrice = $originalTotalPrice;
                    }
                }
                
                $perSizeRemaining[] = [
                    'size_id' => $size->id,
                    'size_name' => $size->size_name,
                    'original_tray_count' => $originalTrayCount,
                    'remaining_tray_count' => $remainingTrayCount,
                    'original_price_per_tray' => $originalPricePerTray,
                    'remaining_price_per_tray' => $originalPricePerTray,
                    'original_total_price' => $originalTotalPrice,
                    'remaining_total_price' => $remainingTotalPrice
                ];
            }
            
            // Calculate new remaining totals
            $newRemainingTotalTrays = 0;
            $newRemainingPrice = 0;
            foreach ($perSizeRemaining as $sizeData) {
                $newRemainingTotalTrays += $sizeData['remaining_tray_count'];
                $newRemainingPrice += $sizeData['remaining_total_price'];
            }
            
            $product->remainingInventory->update([
                'original_quantity' => $product->quantity,
                'original_price' => $product->price,
                'original_total_trays' => $product->sizes->sum('tray_count'),
                'remaining_quantity' => $newRemainingQuantity,
                'remaining_price' => $newRemainingPrice,
                'remaining_total_trays' => $newRemainingTotalTrays,
                'per_size_remaining' => $perSizeRemaining,
                'last_updated' => now()
            ]);
            
            // Update product status based on remaining quantity
            // Only update status automatically if it's not manually set to Sold Out
            if ($product->status !== 'Sold Out' && $newRemainingQuantity <= 0) {
                $product->update([
                    'status' => 'Sold Out'
                ]);
            } else if ($product->status === 'Sold Out' && $newRemainingQuantity > 0) {
                // If product was sold out but now has inventory, mark as available
                $product->update([
                    'status' => 'Available'
                ]);
            }
        }

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
     * Update product status (Available/Sold Out)
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
        
        $request->validate([
            'status' => 'required|in:Available,Sold Out'
        ]);
        
        $product->status = $request->input('status');
        $product->save();
        
        // Update remaining inventory when status changes
        if ($product->remainingInventory) {
            if ($request->input('status') === 'Sold Out') {
                // When marking as sold out, set remaining values to zero
                $perSizeRemaining = [];
                foreach ($product->remainingInventory->per_size_remaining ?? [] as $sizeData) {
                    $perSizeRemaining[] = [
                        'size_id' => $sizeData['size_id'],
                        'size_name' => $sizeData['size_name'],
                        'original_tray_count' => $sizeData['original_tray_count'],
                        'remaining_tray_count' => 0,
                        'original_price_per_tray' => $sizeData['original_price_per_tray'],
                        'remaining_price_per_tray' => $sizeData['original_price_per_tray'],
                        'original_total_price' => $sizeData['original_total_price'],
                        'remaining_total_price' => 0
                    ];
                }
                
                $product->remainingInventory->update([
                    'remaining_quantity' => 0,
                    'remaining_price' => 0,
                    'remaining_total_trays' => 0,
                    'per_size_remaining' => $perSizeRemaining,
                    'last_updated' => now()
                ]);
            } else {
                // When marking as available, restore remaining values to original values
                $perSizeRemaining = [];
                foreach ($product->sizes as $size) {
                    $perSizeRemaining[] = [
                        'size_id' => $size->id,
                        'size_name' => $size->size_name,
                        'original_tray_count' => $size->tray_count,
                        'remaining_tray_count' => $size->tray_count,
                        'original_price_per_tray' => $size->price_per_tray,
                        'remaining_price_per_tray' => $size->price_per_tray,
                        'original_total_price' => $size->total_price,
                        'remaining_total_price' => $size->total_price
                    ];
                }
                
                $product->remainingInventory->update([
                    'remaining_quantity' => $product->quantity,
                    'remaining_price' => $product->price,
                    'remaining_total_trays' => $product->sizes->sum('tray_count'),
                    'per_size_remaining' => $perSizeRemaining,
                    'last_updated' => now()
                ]);
            }
        }
        
        return redirect()->back()->with('success', 'Product status updated successfully.');
    }
}