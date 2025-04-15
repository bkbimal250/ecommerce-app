<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id', 'amount', 'method', 'status', 'transaction_id', 'payment_details'
    ];
    
    protected $casts = [
        'payment_details' => 'json',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function isSuccessful()
    {
        return $this->status === 'completed';
    }
}