<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'active')->with('images', 'category');
        
        // Apply category filter
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Apply price filter
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'popularity':
                    $query->withCount('orderItems')->orderBy('order_items_count', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }
        
        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();
        
        return view('shop.products.index', compact('products', 'categories'));
    }
    
    public function show(Product $product)
    {
        if ($product->status !== 'active') {
            abort(404);
        }
        
        $product->load('images', 'category', 'attributes.attribute', 'attributes.attributeValue', 'reviews.user');
        
        // Get related products
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->with('images')
            ->take(4)
            ->get();
        
        return view('shop.products.show', compact('product', 'relatedProducts'));
    }
    
    public function category(Category $category)
    {
        if (!$category->is_active) {
            abort(404);
        }
        
        $products = Product::where('category_id', $category->id)
            ->where('status', 'active')
            ->with('images')
            ->paginate(12);
        
        return view('shop.products.category', compact('category', 'products'));
    }
    
    public function search(Request $request)
    {
        $keyword = $request->input('q');
        
        $products = Product::where('status', 'active')
            ->where(function($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                      ->orWhere('description', 'like', '%' . $keyword . '%');
            })
            ->with('images', 'category')
            ->paginate(12);
        
        return view('shop.products.search', compact('products', 'keyword'));
    }
}