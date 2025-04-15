<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code', 'description', 'type', 'discount', 'min_order_amount',
        'max_discount_amount', 'max_uses', 'used_count', 'start_date',
        'end_date', 'status'
    ];
    
    protected $dates = [
        'start_date', 'end_date'
    ];
    
    public function isValid()
    {
        $now = now()->startOfDay();
        
        // Check if coupon is active
        if ($this->status !== 'active') {
            return false;
        }
        
        // Check if coupon is within valid date range
        if ($this->start_date > $now || $this->end_date < $now) {
            return false;
        }
        
        // Check if coupon has reached maximum uses
        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }
        
        return true;
    }
}