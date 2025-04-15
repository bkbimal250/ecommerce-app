@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Shopping Cart</h1>
    </div>
</div>

@if($globalCart && $globalCart->items->count() > 0)
    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($globalCart->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('products.show', $item->product) }}" class="me-3">
                                                    @if($item->product->images->count() > 0)
                                                        <img src="{{ asset('storage/' . $item->product->images->first()->image) }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             style="width: 60px; height: 60px; object-fit: cover;" 
                                                             class="rounded">
                                                    @else
                                                        <img src="https://via.placeholder.com/60x60?text=No+Image" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="rounded">
                                                    @endif
                                                </a>
                                                <div>
                                                    <a href="{{ route('products.show', $item->product) }}" class="text-decoration-none text-dark">
                                                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                    </a>
                                                    @if($item->attributes)
                                                        <small class="text-muted">
                                                            @php
                                                                $attributes = json_decode($item->attributes, true);
                                                            @endphp
                                                            @if($attributes)
                                                                @foreach($attributes as $attributeId => $valueId)
                                                                    @php
                                                                        $attribute = App\Models\Attribute::find($attributeId);
                                                                        $value = App\Models\AttributeValue::find($valueId);
                                                                    @endphp
                                                                    @if($attribute && $value)
                                                                        <span>{{ $attribute->name }}: {{ $value->value }}</span><br>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($item->product->sale_price)
                                                <span class="text-decoration-line-through text-muted me-2">${{ number_format($item->product->price, 2) }}</span>
                                                <span class="fw-bold text-danger">${{ number_format($item->product->sale_price, 2) }}</span>
                                            @else
                                                <span class="fw-bold">${{ number_format($item->product->price, 2) }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <div class="input-group input-group-sm" style="width: 120px;">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="decrementCartQuantity('quantity-{{ $item->id }}')">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="form-control text-center" 
                                                           id="quantity-{{ $item->id }}" 
                                                           name="quantity" 
                                                           value="{{ $item->quantity }}" 
                                                           min="1" 
                                                           max="{{ $item->product->quantity }}"
                                                           onchange="this.form.submit()">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="incrementCartQuantity('quantity-{{ $item->id }}', {{ $item->product->quantity }})">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-end align-middle fw-bold">
                                            @php
                                                $unitPrice = $item->product->sale_price ?? $item->product->price;
                                                $subtotal = $unitPrice * $item->quantity;
                                            @endphp
                                            ${{ number_format($subtotal, 2) }}
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('cart.remove', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to remove this item?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                    </a>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#clearCartModal">
                        <i class="fas fa-trash me-2"></i> Clear Cart
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Cart Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-bold">${{ number_format($globalCart->total_amount, 2) }}</span>
                    </div>
                    
                    @php
                        $discount = 0;
                        $coupon = session('coupon');
                        if ($coupon) {
                            if ($coupon['type'] === 'percentage') {
                                $discount = ($globalCart->total_amount * $coupon['discount']) / 100;
                                if (isset($coupon['max_discount_amount']) && $discount > $coupon['max_discount_amount']) {
                                    $discount = $coupon['max_discount_amount'];
                                }
                            } else {
                                $discount = $coupon['discount'];
                            }
                        }
                        
                        $shipping = 0; // You can calculate shipping based on your business logic
                        $tax = ($globalCart->total_amount - $discount) * 0.1; // Assuming 10% tax rate
                        $total = $globalCart->total_amount - $discount + $shipping + $tax;
                    @endphp
                    
                    @if($coupon)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount ({{ $coupon['code'] }})</span>
                            <span>-${{ number_format($discount, 2) }}</span>
                        </div>
                    @endif
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        @if($shipping > 0)
                            <span>${{ number_format($shipping, 2) }}</span>
                        @else
                            <span class="text-success">Free</span>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%)</span>
                        <span>${{ number_format($tax, 2) }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold fs-5">${{ number_format($total, 2) }}</span>
                    </div>
                    
                    <!-- Coupon Form -->
                    <div class="mb-3">
                        <div class="accordion" id="couponAccordion">
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header" id="couponHeading">
                                    <button class="accordion-button collapsed bg-light p-2" type="button" data-bs-toggle="collapse" data-bs-target="#couponCollapse" aria-expanded="false" aria-controls="couponCollapse">
                                        Apply coupon code
                                    </button>
                                </h2>
                                <div id="couponCollapse" class="accordion-collapse collapse {{ $coupon ? 'show' : '' }}" aria-labelledby="couponHeading" data-bs-parent="#couponAccordion">
                                    <div class="accordion-body p-2">
                                        @if($coupon)
                                            <div class="alert alert-success mb-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Coupon "{{ $coupon['code'] }}" applied!</span>
                                                    <form action="{{ route('coupons.remove') }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @else
                                            <form action="{{ route('coupons.apply') }}" method="POST">
                                                @csrf
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="code" placeholder="Enter coupon code">
                                                    <button class="btn btn-primary" type="submit">Apply</button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100">
                        Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Clear Cart Modal -->
    <div class="modal fade" id="clearCartModal" tabindex="-1" aria-labelledby="clearCartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clearCartModalLabel">Clear Shopping Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove all items from your cart?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Clear Cart</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h3>Your cart is empty</h3>
                    <p class="text-muted">Looks like you haven't added anything to your cart yet.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-shopping-bag me-2"></i> Start Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Recently Viewed Products -->
@if(isset($recentlyViewed) && count($recentlyViewed) > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Recently Viewed</h3>
            <div class="row row-cols-1 row-cols-md-4 g-4">
                @foreach($recentlyViewed as $product)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            @if($product->sale_price)
                                <div class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</div>
                            @endif
                            <a href="{{ route('products.show', $product) }}">
                                @if($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                @else
                                    <img src="https://via.placeholder.com/300x300?text=No+Image" class="card-img-top" alt="{{ $product->name }}">
                                @endif
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                                </h5>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">{{ $product->category->name }}</span>
                                    <div>
                                        @if($product->sale_price)
                                            <span class="text-decoration-line-through text-muted me-2">${{ number_format($product->price, 2) }}</span>
                                            <span class="fw-bold text-danger">${{ number_format($product->sale_price, 2) }}</span>
                                        @else
                                            <span class="fw-bold">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
    function decrementCartQuantity(inputId) {
        const quantityInput = document.getElementById(inputId);
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            quantityInput.form.submit();
        }
    }
    
    function incrementCartQuantity(inputId, maxQuantity) {
        const quantityInput = document.getElementById(inputId);
        const currentValue = parseInt(quantityInput.value);
        if (currentValue < maxQuantity) {
            quantityInput.value = currentValue + 1;
            quantityInput.form.submit();
        }
    }
</script>
@endsection