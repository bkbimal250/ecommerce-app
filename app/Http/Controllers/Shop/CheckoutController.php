<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = $user->carts()->where('status', 'active')->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        $addresses = $user->addresses;
        
        return view('shop.checkout.index', compact('cart', 'addresses'));
    }
    
    public function process(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cod,card,paypal',
        ]);
        
        $user = Auth::user();
        $cart = $user->carts()->where('status', 'active')->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        try {
            DB::beginTransaction();
            
            // Create order
            $order = new Order([
                'user_id' => $user->id,
                'address_id' => $request->address_id,
                'status' => 'pending',
                'total_amount' => $cart->total_amount,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
            ]);
            
            $order->save();
            
            // Create order items
            foreach ($cart->items as $cartItem) {
                $orderItem = new OrderItem([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'attributes' => $cartItem->attributes,
                ]);
                
                $order->items()->save($orderItem);
                
                // Update product inventory
                $product = $cartItem->product;
                $product->quantity -= $cartItem->quantity;
                $product->save();
            }
            
            // Process payment
            $payment = new Payment([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'method' => $request->payment_method,
                'status' => $request->payment_method === 'cod' ? 'pending' : 'processing',
            ]);
            
            $payment->save();
            
            // Update order status based on payment method
            if ($request->payment_method === 'cod') {
                $order->status = 'processing';
            } else {
                // For card/paypal, we would normally redirect to a payment gateway
                // For this example, we'll just simulate a successful payment
                $payment->status = 'completed';
                $payment->save();
                
                $order->status = 'confirmed';
            }
            
            $order->save();
            
            // Mark cart as completed
            $cart->status = 'completed';
            $cart->save();
            
            DB::commit();
            
            return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('shop.checkout.success', compact('order'));
    }
}