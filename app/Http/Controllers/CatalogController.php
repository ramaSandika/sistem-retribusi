<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display the landing/home page of the thrift shop.
     */
    public function index()
    {
        // Get 6 latest available products
        $latestProducts = Product::with(['category', 'primaryImage'])
            ->where('status', 'available')
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::withCount('products')->get();

        return view('home', compact('latestProducts', 'categories'));
    }

    /**
     * Display the catalog page with filters and search.
     */
    public function shop(Request $request)
    {
        $query = Product::with(['category', 'primaryImage']);

        // Filter by search query
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category slug
        if ($request->filled('kategori')) {
            $categorySlug = $request->input('kategori');
            $query->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Filter by size
        if ($request->filled('ukuran')) {
            $query->where('size', $request->input('ukuran'));
        }

        // Filter by condition
        if ($request->filled('kondisi')) {
            $query->where('condition', $request->input('kondisi'));
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        // Filter by status (available / sold_out)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // Paginate results (12 per page - perfect for 2, 3, 4 columns grid)
        $products = $query->paginate(12)->withQueryString();
        
        $categories = Category::all();
        $sizes = Product::select('size')->whereNotNull('size')->where('size', '!=', '')->distinct()->pluck('size')->sort();
        $conditions = Product::select('condition')->whereNotNull('condition')->where('condition', '!=', '')->distinct()->pluck('condition');

        return view('catalog', compact('products', 'categories', 'sizes', 'conditions'));
    }

    /**
     * Display the detailed product page.
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'images'])->where('slug', $slug)->firstOrFail();
        
        // WhatsApp number of the store
        $whatsappNumber = env('WHATSAPP_NUMBER', '6285954054217');
        
        $productUrl = route('catalog.show', $product->slug);
        $priceFormatted = 'Rp' . number_format($product->price, 0, ',', '.');
        $message = "Hello Admin, is this item still available?\n\n" .
                   "Item: {$product->name}\n" .
                   "Price: {$priceFormatted}\n" .
                   "Link: {$productUrl}";
        
        $whatsappLink = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

        // Get related products in same category (excluding this product)
        $relatedProducts = Product::with(['category', 'primaryImage'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'available')
            ->take(4)
            ->get();

        return view('detail', compact('product', 'whatsappLink', 'relatedProducts'));
    }

    /**
     * Display the About Us page.
     */
    public function about()
    {
        return view('about');
    }
}
