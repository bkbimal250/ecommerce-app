@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    
    <!-- Dashboard Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">${{ number_format($totalSales, 2) }}</h5>
                            <div class="small">Total Sales</div>
                        </div>
                        <div>
                            <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.orders.index') }}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ $totalOrders }}</h5>
                            <div class="small">Total Orders</div>
                        </div>
                        <div>
                            <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.orders.index') }}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ $totalProducts }}</h5>
                            <div class="small">Total Products</div>
                        </div>
                        <div>
                            <i class="fas fa-box fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.products.index') }}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ $totalUsers }}</h5>
                            <div class="small">Total Users</div>
                        </div>
                        <div>
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.users.index') }}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Monthly Sales ({{ date('Y') }})
                </div>
                <div class="card-body">
                    <canvas id="monthlySalesChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Top Selling Products
                </div>
                <div class="card-body">
                    <canvas id="topProductsChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders and Sales Overview -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Recent Orders
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>${{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($order->status == 'processing')
                                                <span class="badge bg-info">Processing</span>
                                            @elseif($order->status == 'confirmed')
                                                <span class="badge bg-primary">Confirmed</span>
                                            @elseif($order->status == 'shipped')
                                                <span class="badge bg-info">Shipped</span>
                                            @elseif($order->status == 'delivered')
                                                <span class="badge bg-success">Delivered</span>
                                            @elseif($order->status == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @elseif($order->status == 'refunded')
                                                <span class="badge bg-secondary">Refunded</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No recent orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">View All Orders</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Order Status Overview
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart" width="100%" height="50"></canvas>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-list me-1"></i>
                    Top Selling Products
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($topSellingProducts as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">{{ $product->name }}</span>
                                    <br>
                                    <small class="text-muted">Total Units: {{ $product->total_quantity }}</small>
                                </div>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </li>
                        @empty
                            <li class="list-group-item text-center">No data available</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Sales Chart
        const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
        const monthlySalesData = @json($monthlySalesData);
        
        const months = [
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        const monthlySalesChart = new Chart(monthlySalesCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Sales ($)',
                    data: Object.values(monthlySalesData),
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 2,
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
        
        // Top Products Chart
        const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        const topProductsChart = new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($topSellingProducts->pluck('name')) !!},
                datasets: [{
                    label: 'Units Sold',
                    data: {!! json_encode($topSellingProducts->pluck('total_quantity')) !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Order Status Chart
        // You would need to pass order status data from your controller
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        // Example data - replace with actual data from your controller
        const orderStatusData = {
            labels: ['Pending', 'Processing', 'Confirmed', 'Shipped', 'Delivered', 'Cancelled', 'Refunded'],
            datasets: [{
                data: [10, 15, 5, 8, 30, 3, 2], // Replace with actual counts
                backgroundColor: [
                    'rgba(255, 193, 7, 0.7)',  // warning - pending
                    'rgba(13, 202, 240, 0.7)', // info - processing
                    'rgba(13, 110, 253, 0.7)', // primary - confirmed
                    'rgba(13, 202, 240, 0.7)', // info - shipped
                    'rgba(25, 135, 84, 0.7)',  // success - delivered
                    'rgba(220, 53, 69, 0.7)',  // danger - cancelled
                    'rgba(108, 117, 125, 0.7)' // secondary - refunded
                ],
                borderColor: [
                    'rgba(255, 193, 7, 1)',
                    'rgba(13, 202, 240, 1)',
                    'rgba(13, 110, 253, 1)',
                    'rgba(13, 202, 240, 1)',
                    'rgba(25, 135, 84, 1)',
                    'rgba(220, 53, 69, 1)',
                    'rgba(108, 117, 125, 1)'
                ],
                borderWidth: 1
            }]
        };
        
        const orderStatusChart = new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: orderStatusData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    });
</script>
@endsection