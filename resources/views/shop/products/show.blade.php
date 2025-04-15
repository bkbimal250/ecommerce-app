@extends('layouts.app')

@section('title', $product->name)

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Shop</a></li>
        <li class="breadcrumb-item"><a href="{{ route('category.show', $product->category) }}">{{ $product->category->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row mb-5">
    <!-- Product Images -->
    <div class="col-md-6 mb-4 mb-md-0">
        <div class="card border-0 shadow-sm">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach($product->images as $key => $image)
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="{{ $key }}" 
                                class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}" 
                                aria-label="Slide {{ $key + 1 }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach($product->images as $key => $image)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->image) }}" class="d-block w-100" alt="{{ $product->name }}">
                        </div>
                    @endforeach
                    
                    @if($product->images->count() === 0)
                        <div class="carousel-item active">
                            <img src="https://via.placeholder.com/600x600?text=No+Image" class="d-block w-100" alt="{{ $product->name }}">
                        </div>
                    @endif
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            
            @if($product->images->count() > 1)
                <div class="row mt-2 px-2 pb-2">
                    @foreach($product->images as $key => $image)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $image->image) }}" 
                                 class="img-thumbnail {{ $key == 0 ? 'border-primary' : '' }}" 
                                 alt="{{ $product->name }}" 
                                 data-bs-target="#productCarousel" 
                                 data-bs-slide-to="{{ $key }}" 
                                 style="cursor: pointer;">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    
    <!-- Product Details -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-3">{{ $product->name }}</h2>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $product->reviews->avg('rating'))
                                <i class="fas fa-star text-warning"></i>
                            @elseif($i - 0.5 <= $product->reviews->avg('rating'))
                                <i class="fas fa-star-half-alt text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-muted">{{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }}</span>
                </div>
                
                <div class="mb-3">
                    <span class="badge bg-primary">{{ $product->category->name }}</span>
                    @if($product->quantity > 0)
                        <span class="badge bg-success">In Stock</span>
                    @else
                        <span class="badge bg-danger">Out of Stock</span>
                    @endif
                </div>
                
                <div class="mb-3">
                    @if($product->sale_price)
                        <h3 class="text-danger mb-0">${{ number_format($product->sale_price, 2) }}</h3>
                        <p class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</p>
                    @else
                        <h3 class="mb-0">${{ number_format($product->price, 2) }}</h3>
                    @endif
                </div>
                
                <hr>
                
                <div class="mb-4">
                    <p>{{ $product->description }}</p>
                </div>
                
                @if($product->attributes->count() > 0)
                    <div class="mb-4">
                        <h5>Options</h5>
                        @foreach($product->attributes->groupBy('attribute_id') as $attributeGroup)
                            @php
                                $attribute = $attributeGroup->first()->attribute;
                            @endphp
                            <div class="mb-3">
                                <label class="form-label">{{ $attribute->name }}</label>
                                @if(in_array($attribute->type, ['select', 'radio']))
                                    <div class="d-flex flex-wrap">
                                        @foreach($attributeGroup as $productAttribute)
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="{{ $attribute->type === 'select' ? 'radio' : 'radio' }}" 
                                                       name="attributes[{{ $attribute->id }}]" 
                                                       value="{{ $productAttribute->attribute_value_id }}" 
                                                       id="attr-{{ $attribute->id }}-{{ $productAttribute->attribute_value_id }}">
                                                <label class="form-check-label" for="attr-{{ $attribute->id }}-{{ $productAttribute->attribute_value_id }}">
                                                    {{ $productAttribute->attributeValue->value }}
                                                    @if($productAttribute->price_adjustment != 0)
                                                        ({{ $productAttribute->price_adjustment > 0 ? '+' : '' }}${{ number_format($productAttribute->price_adjustment, 2) }})
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($attribute->type === 'checkbox')
                                    <div class="d-flex flex-wrap">
                                        @foreach($attributeGroup as $productAttribute)
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="attributes[{{ $attribute->id }}][]" 
                                                       value="{{ $productAttribute->attribute_value_id }}" 
                                                       id="attr-{{ $attribute->id }}-{{ $productAttribute->attribute_value_id }}">
                                                <label class="form-check-label" for="attr-{{ $attribute->id }}-{{ $productAttribute->attribute_value_id }}">
                                                    {{ $productAttribute->attributeValue->value }}
                                                    @if($productAttribute->price_adjustment != 0)
                                                        ({{ $productAttribute->price_adjustment > 0 ? '+' : '' }}${{ number_format($productAttribute->price_adjustment, 2) }})
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
                
                <!-- Add to Cart Form -->
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label">Quantity</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity()">-</button>
                                <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="{{ $product->quantity }}">
                                <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity()">+</button>
                            </div>
                        </div>
                        <div class="col-md-8 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary flex-grow-1" {{ $product->quantity <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="d-flex">
                    <form action="{{ route('wishlist.add', $product) }}" method="POST" class="me-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-heart me-2"></i> Add to Wishlist
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#shareModal">
                        <i class="fas fa-share-alt me-2"></i> Share
                    </button>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between small text-muted">
                    <div>SKU: {{ $product->sku ?? 'N/A' }}</div>
                    <div>Category: <a href="{{ route('category.show', $product->category) }}" class="text-decoration-none">{{ $product->category->name }}</a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Tabs -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab" aria-controls="specifications" aria-selected="false">Specifications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">
                            Reviews ({{ $product->reviews->count() }})
                        </button>
                    </li>
                </ul>
                <div class="tab-content p-3" id="productTabsContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <div class="p-3">
                            {{ $product->description }}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                        <div class="p-3">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th>Brand</th>
                                        <td>{{ $product->meta_data['brand'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Weight</th>
                                        <td>{{ $product->weight ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dimensions</th>
                                        <td>{{ $product->meta_data['dimensions'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Material</th>
                                        <td>{{ $product->meta_data['material'] ?? 'N/A' }}</td>
                                    </tr>
                                    <!-- Additional specifications can be added here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <div class="p-3">
                            <!-- Review Summary -->
                            <div class="row mb-4">
                                <div class="col-md-4 text-center">
                                    <h1 class="display-4 fw-bold">{{ number_format($product->reviews->avg('rating'), 1) }}</h1>
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $product->reviews->avg('rating'))
                                                <i class="fas fa-star text-warning"></i>
                                            @elseif($i - 0.5 <= $product->reviews->avg('rating'))
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-muted">{{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }}</p>
                                </div>
                                <div class="col-md-8">
                                    <div class="row align-items-center">
                                        <div class="col-3 text-end">5 stars</div>
                                        <div class="col-7">
                                            <div class="progress">
                                                @php
                                                    $fiveStarPercentage = $product->reviews->count() > 0 
                                                        ? $product->reviews->where('rating', 5)->count() / $product->reviews->count() * 100 
                                                        : 0;
                                                @endphp
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $fiveStarPercentage }}%" aria-valuenow="{{ $fiveStarPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-2">{{ $product->reviews->where('rating', 5)->count() }}</div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-3 text-end">4 stars</div>
                                        <div class="col-7">
                                            <div class="progress">
                                                @php
                                                    $fourStarPercentage = $product->reviews->count() > 0 
                                                        ? $product->reviews->where('rating', 4)->count() / $product->reviews->count() * 100 
                                                        : 0;
                                                @endphp
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $fourStarPercentage }}%" aria-valuenow="{{ $fourStarPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-2">{{ $product->reviews->where('rating', 4)->count() }}</div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-3 text-end">3 stars</div>
                                        <div class="col-7">
                                            <div class="progress">
                                                @php
                                                    $threeStarPercentage = $product->reviews->count() > 0 
                                                        ? $product->reviews->where('rating', 3)->count() / $product->reviews->count() * 100 
                                                        : 0;
                                                @endphp
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $threeStarPercentage }}%" aria-valuenow="{{ $threeStarPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-2">{{ $product->reviews->where('rating', 3)->count() }}</div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-3 text-end">2 stars</div>
                                        <div class="col-7">
                                            <div class="progress">
                                                @php
                                                    $twoStarPercentage = $product->reviews->count() > 0 
                                                        ? $product->reviews->where('rating', 2)->count() / $product->reviews->count() * 100 
                                                        : 0;
                                                @endphp
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $twoStarPercentage }}%" aria-valuenow="{{ $twoStarPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-2">{{ $product->reviews->where('rating', 2)->count() }}</div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-3 text-end">1 star</div>
                                        <div class="col-7">
                                            <div class="progress">
                                                @php
                                                    $oneStarPercentage = $product->reviews->count() > 0 
                                                        ? $product->reviews->where('rating', 1)->count() / $product->reviews->count() * 100 
                                                        : 0;
                                                @endphp
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $oneStarPercentage }}%" aria-valuenow="{{ $oneStarPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-2">{{ $product->reviews->where('rating', 1)->count() }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Leave a Review -->
                            @auth
                                <div class="mb-4">
                                    <h5>Write a Review</h5>
                                    <form action="{{ route('reviews.store', $product) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="rating" class="form-label">Rating</label>
                                            <div class="rating">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="rating-5" value="5" required>
                                                    <label class="form-check-label" for="rating-5">5 stars</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="rating-4" value="4">
                                                    <label class="form-check-label" for="rating-4">4 stars</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="rating-3" value="3">
                                                    <label class="form-check-label" for="rating-3">3 stars</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="rating-2" value="2">
                                                    <label class="form-check-label" for="rating-2">2 stars</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="rating-1" value="1">
                                                    <label class="form-check-label" for="rating-1">1 star</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title</label>
                                            <input type="text" class="form-control" id="title" name="title" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="comment" class="form-label">Comment</label>
                                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Review</button>
                                    </form>
                                </div>
                            @else
                                <div class="alert alert-info mb-4">
                                    <p class="mb-0">Please <a href="{{ route('login') }}">log in</a> to write a review.</p>
                                </div>
                            @endauth
                            
                            <!-- Review List -->
                            <div>
                                <h5>Customer Reviews</h5>
                                @if($product->reviews->count() > 0)
                                    @foreach($product->reviews as $review)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <h5 class="card-title mb-0">{{ $review->title }}</h5>
                                                    <div>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <i class="fas fa-star text-warning"></i>
                                                            @else
                                                                <i class="far fa-star text-warning"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                <p class="card-text">{{ $review->comment }}</p>
                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted">By {{ $review->user->name }}</small>
                                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                                </div>
                                                @if($review->is_verified_purchase)
                                                    <div class="mt-2">
                                                        <span class="badge bg-success">Verified Purchase</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="alert alert-light">
                                        <p class="mb-0">No reviews yet. Be the first to review this product!</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
<div class="row mb-5">
    <div class="col-12">
        <h3 class="mb-4">Related Products</h3>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach($relatedProducts ?? [] as $relatedProduct)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($relatedProduct->sale_price)
                            <div class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</div>
                        @endif
                        <a href="{{ route('products.show', $relatedProduct) }}">
                            @if($relatedProduct->images->count() > 0)
                                <img src="{{ asset('storage/' . $relatedProduct->images->first()->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x300?text=No+Image" class="card-img-top" alt="{{ $relatedProduct->name }}">
                            @endif
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="text-decoration-none text-dark">{{ $relatedProduct->name }}</a>
                            </h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">{{ $relatedProduct->category->name }}</span>
                                <div>
                                    @if($relatedProduct->sale_price)
                                        <span class="text-decoration-line-through text-muted me-2">${{ number_format($relatedProduct->price, 2) }}</span>
                                        <span class="fw-bold text-danger">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                                    @else
                                        <span class="fw-bold">${{ number_format($relatedProduct->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                <form action="{{ route('cart.add', $relatedProduct) }}" method="POST">
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

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share This Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-outline-primary mx-2">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->name) }}" target="_blank" class="btn btn-outline-info mx-2">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(request()->url()) }}&media={{ urlencode($product->images->count() > 0 ? asset('storage/' . $product->images->first()->image) : '') }}&description={{ urlencode($product->name) }}" target="_blank" class="btn btn-outline-danger mx-2">
                        <i class="fab fa-pinterest"></i> Pinterest
                    </a>
                </div>
                <div class="mt-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="productUrl" value="{{ request()->url() }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyProductUrl()">Copy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function decrementQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    }
    
    function incrementQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        const maxValue = parseInt(quantityInput.getAttribute('max'));
        if (currentValue < maxValue) {
            quantityInput.value = currentValue + 1;
        }
    }
    
    function copyProductUrl() {
        const urlInput = document.getElementById('productUrl');
        urlInput.select();
        document.execCommand('copy');
        alert('URL copied to clipboard!');
    }
    
    // Initialize image thumbnails click handlers
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.img-thumbnail');
        thumbnails.forEach(function(thumbnail) {
            thumbnail.addEventListener('click', function() {
                const slideIndex = this.getAttribute('data-bs-slide-to');
                const carousel = document.getElementById('productCarousel');
                const bsCarousel = new bootstrap.Carousel(carousel);
                bsCarousel.to(parseInt(slideIndex));
                
                // Update active thumbnail
                thumbnails.forEach(thumb => thumb.classList.remove('border-primary'));
                this.classList.add('border-primary');
            });
        });
        
        // Update thumbnails when carousel slides
        const carousel = document.getElementById('productCarousel');
        carousel.addEventListener('slid.bs.carousel', function(event) {
            const slideIndex = event.to;
            thumbnails.forEach(function(thumb, index) {
                if (index === slideIndex) {
                    thumb.classList.add('border-primary');
                } else {
                    thumb.classList.remove('border-primary');
                }
            });
        });
    });
</script>
@endsection