<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with product metrics.
     */
    public function index()
    {
        $totalProducts = Product::count();
        $availableProducts = Product::where('status', 'available')->count();
        $soldOutProducts = Product::where('status', 'sold_out')->count();

        // Get recent products uploaded (limit 5)
        $recentProducts = Product::with('category')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'availableProducts',
            'soldOutProducts',
            'recentProducts'
        ));
    }
}
