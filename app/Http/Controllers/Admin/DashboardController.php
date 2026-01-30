<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with statistics.
     */
    public function index()
    {
        // Total counts
        $totalOrders = Order::count();
        $totalUsers = User::where('type', 'client')->count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        // Order statistics
        $pendingOrders = Order::where('status', 'pending')->count();
        $confirmedOrders = Order::where('status', 'confirmed')->count();
        $deliveringOrders = Order::where('status', 'out_for_delivery')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        // Revenue statistics
        $totalRevenue = Order::whereIn('status', ['delivered'])->sum('total');
        $pendingRevenue = Order::whereIn('status', ['pending', 'confirmed', 'preparing', 'out_for_delivery'])->sum('total');

        // Recent orders
        $recentOrders = Order::with(['user', 'shop'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        // Top products (by order items)
        $topProducts = Product::withCount('orderItems')
            ->having('order_items_count', '>', 0)
            ->orderBy('order_items_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard_adminlte', compact(
            'totalOrders',
            'totalUsers',
            'totalProducts',
            'totalCategories',
            'pendingOrders',
            'confirmedOrders',
            'deliveringOrders',
            'deliveredOrders',
            'totalRevenue',
            'pendingRevenue',
            'recentOrders',
            'ordersByStatus',
            'topProducts'
        ));
    }
}
