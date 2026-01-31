<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Collection;

class OrderController extends Controller
{
    /**
     * Exibe lista de pedidos da loja autenticada.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Buscar produtos da loja
        $productIds = $user->products()->pluck('id')->toArray();
        
        // Buscar pedidos que contêm produtos desta loja
        $orders = Order::whereHas('items', function ($query) use ($productIds) {
            $query->whereIn('product_id', $productIds);
        })
            ->with(['customer', 'items.product'])
            ->latest()
            ->paginate(15);
        
        // Estatísticas
        $stats = [
            'total_orders' => $orders->total(),
            'pending_orders' => Order::whereHas('items', function ($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            })->where('status', 'pending')->count(),
            'processing_orders' => Order::whereHas('items', function ($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            })->whereIn('status', ['confirmed', 'preparing'])->count(),
            'completed_orders' => Order::whereHas('items', function ($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            })->where('status', 'delivered')->count(),
        ];
        
        return view('loja.orders.index', compact('orders', 'stats'));
    }

    /**
     * Exibe detalhes de um pedido específico.
     */
    public function show(Order $order): View
    {
        $user = auth()->user();
        $productIds = $user->products()->pluck('id')->toArray();
        
        // Verificar se o pedido contém produtos desta loja
        $hasOwnershipOfOrder = $order->items()
            ->whereIn('product_id', $productIds)
            ->exists();
        
        if (!$hasOwnershipOfOrder) {
            abort(403, 'Você não tem permissão para visualizar este pedido.');
        }
        
        $order->load(['customer', 'items.product', 'address']);
        
        return view('loja.orders.show', compact('order'));
    }

    /**
     * Atualiza o status de um pedido.
     */
    public function updateStatus(Order $order): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        $productIds = $user->products()->pluck('id')->toArray();
        
        // Verificar autorização
        $hasOwnershipOfOrder = $order->items()
            ->whereIn('product_id', $productIds)
            ->exists();
        
        if (!$hasOwnershipOfOrder) {
            abort(403, 'Você não tem permissão para atualizar este pedido.');
        }
        
        $status = request()->validate([
            'status' => 'required|in:pending,confirmed,preparing,out_for_delivery,delivered,cancelled',
        ])['status'];
        
        // Não permitir cancelamento de pedidos já entregues
        if ($order->status === 'delivered' || $order->status === 'cancelled') {
            return back()->with('error', 'Não é possível atualizar o status deste pedido.');
        }
        
        $order->update(['status' => $status]);
        
        return back()->with('success', 'Status do pedido atualizado com sucesso.');
    }
}
