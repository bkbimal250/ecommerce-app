<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total sales, orders, products, and users
        $totalSales = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::where('is_admin', false)->count();
        
        // Get recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get monthly sales data for chart
        $monthlySalesData = Order::where('status', '!=', 'cancelled')
            ->whereYear('created_at', now()->year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->map(function ($item) {
                return round($item->total, 2);
            })
            ->toArray();
        
        // Fill in missing months with zero
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($monthlySalesData[$i])) {
                $monthlySalesData[$i] = 0;
            }
        }
        ksort($monthlySalesData);
        
        // Get top selling products
        $topSellingProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard.index', compact(
            'totalSales',
            'totalOrders', 
            'totalProducts', 
            'totalUsers',
            'recentOrders',
            'monthlySalesData',
            'topSellingProducts'
        ));
    }
}