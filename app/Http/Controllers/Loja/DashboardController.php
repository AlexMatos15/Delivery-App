<?php

namespace App\Http\Controllers\Loja;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard da loja.
     */
    public function index(): View
    {
        $loja = auth()->user();

        // IDs dos produtos da loja
        $productIds = $loja->products()->pluck('id');

        // Pedidos aguardando (pendentes)
        $pedidosAguardando = Order::whereHas('items', function ($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })->where('status', 'pending')->count();

        // Métricas do dia (hoje)
        $hoje = now()->startOfDay();
        $fimDoDia = now()->endOfDay();

        $pedidosHoje = Order::whereHas('items', function ($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })->whereBetween('created_at', [$hoje, $fimDoDia])->count();

        $faturamentoHoje = Order::whereHas('items', function ($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })
            ->whereBetween('created_at', [$hoje, $fimDoDia])
            ->whereIn('payment_status', ['paid', 'processing'])
            ->sum('total');

        $pedidosCanceladosHoje = Order::whereHas('items', function ($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })
            ->whereBetween('created_at', [$hoje, $fimDoDia])
            ->where('status', 'cancelled')
            ->count();

        // Últimos 5 pedidos
        $ultimosPedidos = Order::whereHas('items', function ($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('loja.dashboard', compact(
            'loja',
            'pedidosAguardando',
            'pedidosHoje',
            'faturamentoHoje',
            'pedidosCanceladosHoje',
            'ultimosPedidos'
        ));
    }

    /**
     * Alterna o status da loja (aberta/fechada).
     */
    public function toggleStatus(Request $request): RedirectResponse
    {
        $loja = auth()->user();
        $loja->is_open = !$loja->is_open;
        $loja->save();

        $status = $loja->is_open ? 'aberta' : 'fechada';
        return redirect()->route('loja.dashboard')
            ->with('status', "Loja {$status} com sucesso!");
    }
}
