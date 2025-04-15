<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'name', 'address_line1', 'address_line2', 'city', 
        'state', 'zip_code', 'country', 'phone', 'is_default', 'type'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function getFullAddressAttribute()
    {
        $address = $this->address_line1;
        
        if ($this->address_line2) {
            $address .= ', ' . $this->address_line2;
        }
        
        $address .= ', ' . $this->city . ', ' . $this->state . ' ' . $this->zip_code . ', ' . $this->country;
        
        return $address;
    }
}