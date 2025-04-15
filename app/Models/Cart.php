<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'session_id', 'status'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
    
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }
    
    public function getTotalAmountAttribute()
    {
        $total = 0;
        
        foreach ($this->items as $item) {
            $price = $item->product->sale_price ?? $item->product->price;
            $total += $price * $item->quantity;
        }
        
        return $total;
    }
    
    public function applyCoupon(Coupon $coupon)
    {
        $total = $this->total_amount;
        
        // Check minimum order amount
        if ($total < $coupon->min_order_amount) {
            throw new \Exception('Order amount does not meet the minimum requirement for this coupon.');
        }
        
        // Calculate discount
        if ($coupon->type === 'percentage') {
            $discount = ($total * $coupon->discount) / 100;
            
            // Apply maximum discount if set
            if ($coupon->max_discount_amount && $discount > $coupon->max_discount_amount) {
                $discount = $coupon->max_discount_amount;
            }
        } else {
            $discount = $coupon->discount;
        }
        
        return [
            'total_before_discount' => $total,
            'discount' => $discount,
            'total_after_discount' => $total - $discount,
            'coupon' => $coupon
        ];
    }
}