@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Checkout</h1>
    </div>
</div>

@if($cart && $cart->items->count() > 0)
    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
        @csrf
        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        <!-- Addresses Selection -->
                        @if($addresses && $addresses->count() > 0)
                            <div class="mb-4">
                                <label class="form-label">Select a shipping address</label>
                                @foreach($addresses as $address)
                                    <div class="card mb-2 {{ $address->is_default ? 'border-primary' : '' }}">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="address_id" id="address-{{ $address->id }}" 
                                                       value="{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="address-{{ $address->id }}">
                                                    <div>
                                                        <strong>{{ $address->name }}</strong>
                                                        @if($address->is_default)
                                                            <span class="badge bg-primary ms-2">Default</span>
                                                        @endif
                                                    </div>
                                                    <div>{{ $address->address_line1 }}</div>
                                                    @if($address->address_line2)
                                                        <div>{{ $address->address_line2 }}</div>
                                                    @endif
                                                    <div>{{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}</div>
                                                    <div>{{ $address->country }}</div>
                                                    <div>Phone: {{ $address->phone }}</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mb-4">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#newAddressForm">
                                    <i class="fas fa-plus-circle me-2"></i> Add New Address
                                </button>
                            </div>
                        @endif
                        
                        <!-- New Address Form -->
                        <div class="{{ $addresses && $addresses->count() > 0 ? 'collapse' : '' }}" id="newAddressForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address_line1" class="form-label">Address Line 1</label>
                                <input type="text" class="form-control" id="address_line1" name="address_line1" placeholder="Street address, P.O. box, company name, etc." required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address_line2" class="form-label">Address Line 2 <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="address_line2" name="address_line2" placeholder="Apartment, suite, unit, building, floor, etc.">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="state" class="form-label">State/Province/Region</label>
                                    <input type="text" class="form-control" id="state" name="state" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="zip_code" class="form-label">Zip / Postal Code</label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <select class="form-select" id="country" name="country" required>
                                    <option value="">Select country</option>
                                    <option value="United States">United States</option>
                                    <option value="Canada">Canada</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <!-- Add more countries as needed -->
                                </select>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="save_address" name="save_address" value="1" checked>
                                <label class="form-check-label" for="save_address">
                                    Save this address for future use
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1">
                                <label class="form-check-label" for="is_default">
                                    Set as default address
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment-card" value="card" checked required>
                            <label class="form-check-label" for="payment-card">
                                <i class="fab fa-cc-visa me-2"></i>
                                <i class="fab fa-cc-mastercard me-2"></i>
                                <i class="fab fa-cc-amex me-2"></i>
                                <i class="fab fa-cc-discover me-2"></i>
                                Credit/Debit Card
                            </label>
                        </div>
                        
                        <div class="ps-4 mb-4" id="card-payment-details">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="card_number" class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="card_number" placeholder="**** **** **** ****">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="card_name" class="form-label">Name on Card</label>
                                    <input type="text" class="form-control" id="card_name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="text" class="form-control" id="expiry_date" placeholder="MM/YY">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cvv" placeholder="***">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment-paypal" value="paypal" required>
                            <label class="form-check-label" for="payment-paypal">
                                <i class="fab fa-paypal me-2"></i> PayPal
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment-cod" value="cod" required>
                            <label class="form-check-label" for="payment-cod">
                                <i class="fas fa-money-bill-wave me-2"></i> Cash on Delivery
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Special Instructions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Additional Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes <span class="text-muted">(Optional)</span></label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Notes about your order, e.g., special delivery instructions"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4 sticky-md-top" style="top: 20px; z-index: 1;">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Order Summary</h5>
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="mb-3">Products ({{ $cart->items->sum('quantity') }})</h6>
                                @foreach($cart->items as $item)
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0">
                                            @if($item->product->images->count() > 0)
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image) }}" 
                                                    alt="{{ $item->product->name }}" 
                                                    style="width: 50px; height: 50px; object-fit: cover;" 
                                                    class="rounded">
                                            @else
                                                <img src="https://via.placeholder.com/50x50?text=No+Image" 
                                                    alt="{{ $item->product->name }}" 
                                                    class="rounded">
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                <span class="text-muted">x {{ $item->quantity }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">
                                                    @php
                                                        $price = $item->product->sale_price ?? $item->product->price;
                                                        echo '$' . number_format($price, 2);
                                                    @endphp
                                                </small>
                                                <small class="fw-bold">
                                                    @php
                                                        $subtotal = $price * $item->quantity;
                                                        echo '$' . number_format($subtotal, 2);
                                                    @endphp
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span class="fw-bold">${{ number_format($cart->total_amount, 2) }}</span>
                            </div>
                            
                            @php
                                $discount = 0;
                                $coupon = session('coupon');
                                if ($coupon) {
                                    if ($coupon['type'] === 'percentage') {
                                        $discount = ($cart->total_amount * $coupon['discount']) / 100;
                                        if (isset($coupon['max_discount_amount']) && $discount > $coupon['max_discount_amount']) {
                                            $discount = $coupon['max_discount_amount'];
                                        }
                                    } else {
                                        $discount = $coupon['discount'];
                                    }
                                }
                                
                                $shipping = 0; // You can calculate shipping based on your business logic
                                $tax = ($cart->total_amount - $discount) * 0.1; // Assuming 10% tax rate
                                $total = $cart->total_amount - $discount + $shipping + $tax;
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
                            
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold fs-5">${{ number_format($total, 2) }}</span>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I have read and agree to the website <a href="#" target="_blank">terms and conditions</a>
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                Place Order <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                            
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-lock me-1"></i> Your payment information is secure
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                        <h3>Your cart is empty</h3>
                        <p class="text-muted">You need to add items to your cart before proceeding to checkout.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-shopping-bag me-2"></i> Start Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endsection
    
    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentCardRadio = document.getElementById('payment-card');
            const paymentPaypalRadio = document.getElementById('payment-paypal');
            const paymentCodRadio = document.getElementById('payment-cod');
            const cardPaymentDetails = document.getElementById('card-payment-details');
            
            function toggleCardDetails() {
                if (paymentCardRadio.checked) {
                    cardPaymentDetails.style.display = 'block';
                } else {
                    cardPaymentDetails.style.display = 'none';
                }
            }
            
            paymentCardRadio.addEventListener('change', toggleCardDetails);
            paymentPaypalRadio.addEventListener('change', toggleCardDetails);
            paymentCodRadio.addEventListener('change', toggleCardDetails);
            
            // Initialize on page load
            toggleCardDetails();
            
            // Form validation
            const checkoutForm = document.getElementById('checkout-form');
            checkoutForm.addEventListener('submit', function(event) {
                if (paymentCardRadio.checked) {
                    const cardNumber = document.getElementById('card_number').value.trim();
                    const cardName = document.getElementById('card_name').value.trim();
                    const expiryDate = document.getElementById('expiry_date').value.trim();
                    const cvv = document.getElementById('cvv').value.trim();
                    
                    if (!cardNumber || !cardName || !expiryDate || !cvv) {
                        event.preventDefault();
                        alert('Please fill in all card details');
                    }
                }
            });
        });
    </script>
    @endsection