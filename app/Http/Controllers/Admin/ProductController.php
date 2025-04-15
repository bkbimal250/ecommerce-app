<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('admin.products.index', compact('products'));
    }
    
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'featured' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Create product
        $product = new Product([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'featured' => $request->has('featured'),
            'status' => $request->status,
        ]);
        
        $product->save();
        
        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                $productImage = new ProductImage([
                    'product_id' => $product->id,
                    'image' => $path,
                    'is_primary' => $product->images()->count() === 0, // First image is primary
                ]);
                
                $product->images()->save($productImage);
            }
        }
        
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }
    
    // Implement edit, update, destroy methods...
}