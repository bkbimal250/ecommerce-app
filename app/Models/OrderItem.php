<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'attributes'];
    
    protected $casts = [
        'attributes' => 'json',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
    
    public function canBeReviewed()
    {
        // Check if order is delivered and product has not been reviewed yet
        return $this->order->status === 'delivered' && 
               !Review::where('user_id', $this->order->user_id)
                    ->where('product_id', $this->product_id)
                    ->exists();
    }
}