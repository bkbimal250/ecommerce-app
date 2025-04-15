<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product.images')
            ->get();
        
        return view('shop.wishlist.index', compact('wishlists'));
    }
    
    public function add(Product $product)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();
        
        if (!$wishlist) {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id
            ]);
            
            return redirect()->back()->with('success', 'Product added to wishlist');
        }
        
        return redirect()->back()->with('info', 'Product already in wishlist');
    }
    
    public function remove(Product $product)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();
        
        return redirect()->back()->with('success', 'Product removed from wishlist');
    }
}