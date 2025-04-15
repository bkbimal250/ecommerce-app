<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured products
        $featuredProducts = Product::where('featured', true)
            ->where('status', 'active')
            ->with('images')
            ->take(8)
            ->get();
        
        // Get new arrivals
        $newArrivals = Product::where('status', 'active')
            ->with('images')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        
        // Get top categories
        $topCategories = Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(6)
            ->get();
        
        return view('shop.home.index', compact('featuredProducts', 'newArrivals', 'topCategories'));
    }
}