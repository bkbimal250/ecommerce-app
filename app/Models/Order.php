<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'address_id', 'order_number', 'status', 'total_amount',
        'tax_amount', 'shipping_amount', 'discount_amount', 'coupon_code',
        'notes', 'shipped_at', 'delivered_at'
    ];
    
    protected $dates = [
        'shipped_at', 'delivered_at'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }
    
    public function getFinalAmountAttribute()
    {
        return $this->total_amount + $this->tax_amount + $this->shipping_amount - $this->discount_amount;
    }
    
    public function canBeCancelled()
    {
        $cancelableStatuses = ['pending', 'processing', 'confirmed'];
        return in_array($this->status, $cancelableStatuses);
    }
}