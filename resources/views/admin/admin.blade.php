<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('styles')
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="border-end bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-primary text-white p-3">
                <i class="fas fa-shopping-cart me-2"></i> Admin Panel
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action p-3 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action p-3 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box me-2"></i> Products
                </a>
                <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action p-3 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags me-2"></i> Categories
                </a>
                <a href="{{ route('admin.attributes.index') }}" class="list-group-item list-group-item-action p-3 {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                    <i class="fas fa-list me-2"></i> Attributes
                </a>
                <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action p-3 {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart me-2"></i> Orders
                </a>
                <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action p-3 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i> Users
                </a>
                <a href="#" class="list-group-item list-group-item-action p-3">
                    <i class="fas fa-ticket-alt me-2"></i> Coupons
                </a>
                <a href="#" class="list-group-item list-group-item-action p-3">
                    <i class="fas fa-chart-bar me-2"></i> Reports
                </a>
                <a href="#" class="list-group-item list-group-item-action p-3">
                    <i class="fas fa-cog me-2"></i> Settings
                </a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Top navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}" target="_blank">
                                    <i class="fas fa-external-link-alt me-1"></i> View Site
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell me-1"></i>
                                    <span class="badge bg-danger">3</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <h6 class="dropdown-header">Notifications</h6>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-shopping-cart text-primary"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="small text-muted">New Order #12345</div>
                                                <span>John Doe placed a new order</span>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-user text-success"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="small text-muted">New User Registration</div>
                                                <span>Jane Smith created an account</span>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-exclamation-circle text-warning"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="small text-muted">Stock Alert</div>
                                                <span>Product "Smartphone X" is low in stock</span>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-center small text-muted" href="#">View All Notifications</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user-cog me-1"></i> Profile
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cog me-1"></i> Settings
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}" 
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <!-- Page content -->
            <main class="container-fluid p-4">
                @include('layouts.flash-messages')
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.body.classList.toggle('sb-sidenav-toggled');
                    localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
                });
            }
            
            // Check for saved state
            if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
                document.body.classList.add('sb-sidenav-toggled');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>