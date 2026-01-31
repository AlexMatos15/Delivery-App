<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class ReportsController extends Controller
{
    /**
     * Exibe dashboard com relatórios financeiros.
     */
    public function dashboard(): View
    {
        $user = auth()->user();
        $productIds = $user->products()->pluck('id')->toArray();
        
        // Buscar todos os pedidos que contêm produtos desta loja
        $allOrders = Order::whereHas('items', function ($query) use ($productIds) {
            $query->whereIn('product_id', $productIds);
        })->get();
        
        // Calcular receita total
        $totalRevenue = 0;
        $completedOrders = 0;
        
        foreach ($allOrders as $order) {
            // Filtrar apenas itens da loja
            $lojaItems = $order->items()->whereIn('product_id', $productIds)->get();
            
            foreach ($lojaItems as $item) {
                $totalRevenue += $item->price * $item->quantity;
            }
            
            if ($order->status === 'delivered') {
                $completedOrders++;
            }
        }
        
        // Estatísticas por período (últimos 30 dias)
        $revenueLastMonth = 0;
        $ordersLastMonth = 0;
        
        foreach ($allOrders as $order) {
            if ($order->created_at->gte(now()->subDays(30))) {
                $lojaItems = $order->items()->whereIn('product_id', $productIds)->get();
                
                foreach ($lojaItems as $item) {
                    $revenueLastMonth += $item->price * $item->quantity;
                }
                
                $ordersLastMonth++;
            }
        }
        
        // Produtos mais vendidos
        $topProducts = \DB::table('order_items')
            ->whereIn('product_id', $productIds)
            ->select('product_id', \DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'product' => \App\Models\Product::find($item->product_id),
                    'quantity' => $item->total_quantity,
                ];
            });
        
        // Status dos pedidos
        $ordersByStatus = [
            'pending' => Order::whereHas('items', function ($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            })->where('status', 'pending')->count(),
            'confirmed' => Order::whereHas('items', function ($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            })->where('status', 'confirmed')->count(),
            'preparing' => Order::whereHas('items', function ($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            })->where('status', 'preparing')->count(),
            'out_for_delivery' => Order::whereHas('items', function ($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            })->where('status', 'out_for_delivery')->count(),
            'delivered' => Order::whereHas('items', function ($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            })->where('status', 'delivered')->count(),
            'cancelled' => Order::whereHas('items', function ($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            })->where('status', 'cancelled')->count(),
        ];
        
        $stats = [
            'total_revenue' => $totalRevenue,
            'completed_orders' => $completedOrders,
            'revenue_last_month' => $revenueLastMonth,
            'orders_last_month' => $ordersLastMonth,
            'average_order_value' => $allOrders->count() > 0 ? $totalRevenue / $allOrders->count() : 0,
            'total_orders' => $allOrders->count(),
        ];
        
        return view('loja.reports.dashboard', compact('stats', 'topProducts', 'ordersByStatus'));
    }

    /**
     * Exibe relatório de vendas.
     */
    public function sales(): View
    {
        $user = auth()->user();
        $productIds = $user->products()->pluck('id')->toArray();
        
        // Agrupar vendas por dia (últimos 30 dias)
        $dailySales = \DB::table('order_items')
            ->whereIn('product_id', $productIds)
            ->select(
                \DB::raw('DATE(created_at) as date'),
                \DB::raw('COUNT(*) as quantity'),
                \DB::raw('SUM(price * quantity) as revenue')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy(\DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        return view('loja.reports.sales', compact('dailySales'));
    }

    /**
     * Exibe relatório de clientes.
     */
    public function customers(): View
    {
        $user = auth()->user();
        $productIds = $user->products()->pluck('id')->toArray();
        
        // Clientes que compraram desta loja
        $customers = Order::whereHas('items', function ($query) use ($productIds) {
            $query->whereIn('product_id', $productIds);
        })
            ->with('customer')
            ->get()
            ->groupBy('customer_id')
            ->map(function ($orders) {
                $customer = $orders->first()->customer;
                $totalSpent = 0;
                
                foreach ($orders as $order) {
                    foreach ($order->items as $item) {
                        $totalSpent += $item->price * $item->quantity;
                    }
                }
                
                return [
                    'customer' => $customer,
                    'orders_count' => $orders->count(),
                    'total_spent' => $totalSpent,
                    'last_order' => $orders->sortByDesc('created_at')->first()->created_at,
                ];
            })
            ->sortByDesc('total_spent')
            ->take(20);
        
        return view('loja.reports.customers', compact('customers'));
    }
}
