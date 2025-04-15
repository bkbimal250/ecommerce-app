<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'sale_price', 
        'quantity', 'category_id', 'featured', 'status'
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}