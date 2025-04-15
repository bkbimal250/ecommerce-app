<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Other methods...
    
    public function clear()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();
            
        if ($cart) {
            $cart->items()->delete();
            // Or you might want to change status instead of deleting
            // $cart->status = 'abandoned';
            // $cart->save();
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart has been cleared');
    }
}
