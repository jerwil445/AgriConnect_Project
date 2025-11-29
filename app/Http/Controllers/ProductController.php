<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Farmer;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

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
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'harvest_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $farmer = $user->farmer;

        $product = new Product($request->except('images'));
        $product->farmer_id = $farmer->id;
        
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
        
        // Load product images
        $product->load('images');
        
        return view('farmers.products.show', compact('product'));
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
        
        // Load product images
        $product->load('images');
        
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
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'harvest_date' => 'required|date',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array|max:10',
        ]);

        $product->fill($request->except('images'));
        
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
        
        // Delete all associated images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        // Delete image records
        $product->images()->delete();
        
        // Delete primary image if exists and not in images table
        if ($product->image && !$product->images()->where('image_path', $product->image)->exists()) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Product deleted successfully.');
    }

    /**
     * Update the status of the specified product.
     */
    public function updateStatus(Request $request, Product $product)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Authentication required.'], 401);
        }
        
        // Check if user has farmer profile
        $user = Auth::user();
        if (!$user->farmer) {
            return response()->json(['success' => false, 'message' => 'Access denied. Farmer profile required.'], 403);
        }
        
        // Ensure farmer can only update their own products
        if ($product->farmer_id != $user->farmer->id) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }
        
        // Validate status
        $request->validate([
            'status' => 'required|in:Available,Pending,Sold Out',
        ]);
        
        // Update product status
        $product->status = $request->status;
        $product->save();
        
        return response()->json(['success' => true, 'message' => 'Product status updated successfully.']);
    }
}
