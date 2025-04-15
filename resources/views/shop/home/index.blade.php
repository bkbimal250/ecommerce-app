@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Banner -->
<div class="row mb-5">
    <div class="col-12">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner rounded shadow">
                <div class="carousel-item active">
                    <img src="https://via.placeholder.com/1200x400/007BFF/FFFFFF?text=New+Arrivals" class="d-block w-100" alt="New Arrivals">
                    <div class="carousel-caption d-none d-md-block">
                        <h2>New Arrivals</h2>
                        <p>Check out our latest products with amazing offers!</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://via.placeholder.com/1200x400/28A745/FFFFFF?text=Special+Offers" class="d-block w-100" alt="Special Offers">
                    <div class="carousel-caption d-none d-md-block">
                        <h2>Special Offers</h2>
                        <p>Limited time discounts on selected items!</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://via.placeholder.com/1200x400/DC3545/FFFFFF?text=Seasonal+Sale" class="d-block w-100" alt="Seasonal Sale">
                    <div class="carousel-caption d-none d-md-block">
                        <h2>Seasonal Sale</h2>
                        <p>Up to 50% off on seasonal items!</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>

<!-- Featured Categories -->
<div class="row mb-5">
    <div class="col-12">
        <h2 class="section-title mb-4">Shop by Categories</h2>
        <div class="row g-4">
            @foreach($topCategories ?? [] as $category)
                <div class="col-md-4">
                    <a href="{{ route('category.show', $category) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="{{ $category->image ? asset('storage/' . $category->image) : 'https://via.placeholder.com/300x200?text=' . $category->name }}" 
                                 class="card-img-top" alt="{{ $category->name }}">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $category->name }}</h5>
                                <p class="card-text text-muted">{{ $category->products_count ?? 0 }} Products</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Featured Products -->
<div class="row mb-5">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Featured Products</h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">View All</a>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($featuredProducts ?? [] as $product)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="badge bg-danger position-absolute top-0 end-0 m-2">Featured</div>
                        @if($product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x300?text=No+Image" class="card-img-top" alt="{{ $product->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
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
                                        <i class="fas fa-shopping-cart me-1"></i> Add to Cart
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

<!-- New Arrivals -->
<div class="row mb-5">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">New Arrivals</h2>
            <a href="{{ route('products.index') }}?sort=newest" class="btn btn-outline-primary">View All</a>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($newArrivals ?? [] as $product)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="badge bg-success position-absolute top-0 end-0 m-2">New</div>
                        @if($product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x300?text=No+Image" class="card-img-top" alt="{{ $product->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
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
                                        <i class="fas fa-shopping-cart me-1"></i> Add to Cart
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

<!-- Features Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="p-4 bg-light rounded shadow-sm">
                    <i class="fas fa-truck fa-3x mb-3 text-primary"></i>
                    <h5>Free Shipping</h5>
                    <p class="mb-0 text-muted">On orders over $50</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 bg-light rounded shadow-sm">
                    <i class="fas fa-undo fa-3x mb-3 text-primary"></i>
                    <h5>Easy Returns</h5>
                    <p class="mb-0 text-muted">30 days return policy</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 bg-light rounded shadow-sm">
                    <i class="fas fa-lock fa-3x mb-3 text-primary"></i>
                    <h5>Secure Payments</h5>
                    <p class="mb-0 text-muted">100% secure checkout</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 bg-light rounded shadow-sm">
                    <i class="fas fa-headset fa-3x mb-3 text-primary"></i>
                    <h5>24/7 Support</h5>
                    <p class="mb-0 text-muted">Dedicated support</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection