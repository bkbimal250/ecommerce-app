<?php

use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', 'App\Http\Controllers\Shop\HomeController@index')->name('home');
Route::get('/products', 'App\Http\Controllers\Shop\ProductController@index')->name('products.index');
Route::get('/products/{product}', 'App\Http\Controllers\Shop\ProductController@show')->name('products.show');
Route::get('/categories/{category}', 'App\Http\Controllers\Shop\ProductController@category')->name('category.show');
Route::get('/search', 'App\Http\Controllers\Shop\ProductController@search')->name('products.search');

// Authentication routes (provided by Laravel UI)
Auth::routes();

// User account routes
Route::middleware(['auth'])->group(function () {
    Route::get('/account', 'App\Http\Controllers\Shop\UserController@account')->name('user.account');
    Route::put('/account/update', 'App\Http\Controllers\Shop\UserController@update')->name('user.update');
    Route::get('/account/orders', 'App\Http\Controllers\Shop\OrderController@index')->name('user.orders');
    Route::get('/account/orders/{order}', 'App\Http\Controllers\Shop\OrderController@show')->name('user.orders.show');
    
    // Wishlist routes
    Route::get('/wishlist', 'App\Http\Controllers\Shop\WishlistController@index')->name('wishlist.index');
    Route::post('/wishlist/add/{product}', 'App\Http\Controllers\Shop\WishlistController@add')->name('wishlist.add');
    Route::delete('/wishlist/remove/{product}', 'App\Http\Controllers\Shop\WishlistController@remove')->name('wishlist.remove');
    
    // Cart routes
    Route::get('/cart', 'App\Http\Controllers\Shop\CartController@index')->name('cart.index');
    Route::post('/cart/add/{product}', 'App\Http\Controllers\Shop\CartController@add')->name('cart.add');
    Route::put('/cart/update/{cartItem}', 'App\Http\Controllers\Shop\CartController@update')->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', 'App\Http\Controllers\Shop\CartController@remove')->name('cart.remove');
    Route::delete('/cart/clear', 'App\Http\Controllers\Shop\CartController@clear')->name('cart.clear');
    
    // Coupon routes
    Route::post('/coupons/apply', 'App\Http\Controllers\Shop\CouponController@apply')->name('coupons.apply');
    Route::post('/coupons/remove', 'App\Http\Controllers\Shop\CouponController@remove')->name('coupons.remove');
    
    // Checkout routes
    Route::get('/checkout', 'App\Http\Controllers\Shop\CheckoutController@index')->name('checkout.index');
    Route::post('/checkout/process', 'App\Http\Controllers\Shop\CheckoutController@process')->name('checkout.process');
    Route::get('/checkout/success/{order}', 'App\Http\Controllers\Shop\CheckoutController@success')->name('checkout.success');
    
    // Review routes
    Route::post('/reviews/{product}', 'App\Http\Controllers\Shop\ReviewController@store')->name('reviews.store');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', 'App\Http\Controllers\Admin\DashboardController@index')->name('dashboard');
    
    // Product management
    Route::resource('products', 'App\Http\Controllers\Admin\ProductController');
    Route::resource('categories', 'App\Http\Controllers\Admin\CategoryController');
    Route::resource('attributes', 'App\Http\Controllers\Admin\AttributeController');
    
    // Order management
    Route::resource('orders', 'App\Http\Controllers\Admin\OrderController');
    
    // User management
    Route::resource('users', 'App\Http\Controllers\Admin\UserController');
});