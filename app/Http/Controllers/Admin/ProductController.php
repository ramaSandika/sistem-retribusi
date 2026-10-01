<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage']);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'size' => 'required|string|max:50',
            'condition' => 'required|string|max:100',
            'brand' => 'nullable|string|max:100',
            'status' => 'required|in:available,sold_out',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240' // Allow up to 10MB original, we compress it
        ]);

        $productData = $request->except('images');
        $productData['slug'] = Str::slug($request->name) . '-' . time();

        $product = Product::create($productData);

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $index => $imageFile) {
                // Optimize: resize to max 1200px & compress to ~80% quality JPEG
                $path = ImageOptimizer::store($imageFile, 'products');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0 // Make first image primary
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'size' => 'required|string|max:50',
            'condition' => 'required|string|max:100',
            'brand' => 'nullable|string|max:100',
            'status' => 'required|in:available,sold_out',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240' // Allow up to 10MB original, we compress it
        ]);

        $productData = $request->except('images');
        
        // Regenerate slug only if name changes
        if ($product->name !== $request->name) {
            $productData['slug'] = Str::slug($request->name) . '-' . time();
        }

        $product->update($productData);

        // Upload new images if present
        if ($request->hasFile('images')) {
            // Check if product already has primary image
            $hasPrimary = ProductImage::where('product_id', $product->id)->where('is_primary', true)->exists();
            
            $images = $request->file('images');
            foreach ($images as $index => $imageFile) {
                // Optimize: resize to max 1200px & compress to ~80% quality JPEG
                $path = ImageOptimizer::store($imageFile, 'products');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => !$hasPrimary && $index === 0
                ]);

                // Mark that we now have a primary image
                if (!$hasPrimary && $index === 0) {
                    $hasPrimary = true;
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);

        // Delete physical files
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        // Delete database records (images will be deleted via cascade on DB level, but doing it explicitly or letting model handle it)
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Toggle the product availability status.
     */
    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = $product->status === 'available' ? 'sold_out' : 'available';
        $product->save();

        return back()->with('success', 'Status produk berhasil diubah.');
    }

    /**
     * Set an image as primary for a product.
     */
    public function setPrimaryImage($productId, $imageId)
    {
        // Set all images of this product to not primary
        ProductImage::where('product_id', $productId)->update(['is_primary' => false]);

        // Set the chosen image to primary
        $image = ProductImage::where('product_id', $productId)->where('id', $imageId)->firstOrFail();
        $image->is_primary = true;
        $image->save();

        return back()->with('success', 'Foto utama berhasil diubah.');
    }

    /**
     * Delete a single image from a product.
     */
    public function deleteImage($productId, $imageId)
    {
        $image = ProductImage::where('product_id', $productId)->where('id', $imageId)->firstOrFail();

        // Delete file from storage
        Storage::disk('public')->delete($image->image_path);

        $wasPrimary = $image->is_primary;

        // Delete DB record
        $image->delete();

        // If deleted image was primary, make the first remaining image primary
        if ($wasPrimary) {
            $nextImage = ProductImage::where('product_id', $productId)->first();
            if ($nextImage) {
                $nextImage->is_primary = true;
                $nextImage->save();
            }
        }

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
