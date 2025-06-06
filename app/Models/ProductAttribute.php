<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id', 'attribute_id', 'attribute_value_id', 'price_adjustment'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
    
    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }
}