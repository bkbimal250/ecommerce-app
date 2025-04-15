@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="row">
    <!-- Sidebar Filters -->
    <div class="col-lg-3 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Filters</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('products.index') }}" method="GET">
                    <!-- Categories Filter -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Categories</h6>
                        <div class="overflow-auto" style="max-height: 200px;">
                            @foreach($categories ?? [] as $category)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="category[]" 
                                        value="{{ $category->id }}" id="category-{{ $category->id }}"
                                        {{ (request()->has('category') && in_array($category->id, (array)request()->category)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category-{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Price Range</h6>
                        <div class="row g-2">
                            <div class="col">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" placeholder="Min" name="min_price" value="{{ request('min_price') }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" placeholder="Max" name="max_price" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Filters can be added here -->

                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">Reset Filters</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Listing -->
    <div class="col-lg-9">
        <!-- Sorting and Layout Options -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() ?? 0 }} products</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <label for="sort" class="me-2 mb-0">Sort by:</label>
                        <select id="sort" class="form-select form-select-sm" onchange="window.location.href=this.value">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                Newest
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                Price: Low to High
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort')