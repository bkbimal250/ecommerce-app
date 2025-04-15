<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string',
        ]);
        
        // Check if user has purchased the product
        $hasPurchased = Order::where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->whereHas('items', function($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();
        
        // Check if user has already reviewed the product
        $hasReviewed = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();
        
        if ($hasReviewed) {
            return redirect()->back()->with('error', 'You have already reviewed this product');
        }
        
        $review = new Review([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_verified_purchase' => $hasPurchased,
            'is_approved' => !config('app.review_approval_required'), // Auto-approve if not required
        ]);
        
        // If user has purchased, find the order and link it
        if ($hasPurchased) {
            $order = Order::where('user_id', Auth::id())
                ->where('status', 'delivered')
                ->whereHas('items', function($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->latest()
                ->first();
                
            $review->order_id = $order->id;
        }
        
        $review->save();
        
        return redirect()->back()->with('success', 'Your review has been submitted');
    }
}