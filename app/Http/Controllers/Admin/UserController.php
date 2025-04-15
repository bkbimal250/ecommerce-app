<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function account()
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        
        return view('shop.user.account', compact('user', 'addresses'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect()->route('user.account')->with('success', 'Profile updated successfully');
    }
    
    public function storeAddress(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);
        
        $user = Auth::user();
        
        $address = new Address($request->all());
        $address->user_id = $user->id;
        
        if ($request->is_default) {
            // Set all other addresses as non-default
            $user->addresses()->update(['is_default' => false]);
        }
        
        $address->save();
        
        return redirect()->route('user.account')->with('success', 'Address added successfully');
    }
}