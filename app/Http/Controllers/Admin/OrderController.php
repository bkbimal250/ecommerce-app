<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by order number
        if ($request->has('order_number') && $request->order_number) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }
        
        // Sort orders
        $query->orderBy('created_at', 'desc');
        
        $orders = $query->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        $order->load('user', 'address', 'items.product', 'payment');
        return view('admin.orders.show', compact('order'));
    }
    
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,confirmed,shipped,delivered,cancelled,refunded',
            'notes' => 'nullable|string',
        ]);
        
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->notes = $request->notes;
        
        // Set timestamps based on status
        if ($request->status === 'shipped' && $oldStatus !== 'shipped') {
            $order->shipped_at = now();
        }
        
        if ($request->status === 'delivered' && $oldStatus !== 'delivered') {
            $order->delivered_at = now();
        }
        
        $order->save();
        
        // Update payment status if order is cancelled or refunded
        if (in_array($request->status, ['cancelled', 'refunded'])) {
            $payment = $order->payment;
            if ($payment) {
                $payment->status = $request->status === 'cancelled' ? 'failed' : 'refunded';
                $payment->save();
            }
        }
        
        // Send email notification to customer
        if ($oldStatus !== $request->status) {
            Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
        }
        
        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated successfully');
    }
    
    public function invoice(Order $order)
    {
        $order->load('user', 'address', 'items.product', 'payment');
        return view('admin.orders.invoice', compact('order'));
    }
}