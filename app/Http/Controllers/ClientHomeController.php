<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * ClientHomeController
 * 
 * Controlador para a página inicial do cliente
 * Responsável por mostrar produtos disponíveis em estilo app de delivery
 */
class ClientHomeController extends Controller
{
    /**
     * Exibe a página inicial do cliente
     * Mostra produtos em destaque e de forma apelativa
     */
    public function index(Request $request)
    {
        // Lojas ativas (unidades)
        $stores = User::where('type', 'shop')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $selectedStoreId = $request->session()->get('store_id');
        $selectedStore = $selectedStoreId
            ? $stores->firstWhere('id', (int) $selectedStoreId)
            : null;

        // Se a unidade salva não existir mais, limpa a sessão
        if ($selectedStoreId && !$selectedStore) {
            $request->session()->forget('store_id');
            $selectedStoreId = null;
        }

        $products = collect();
        $categories = collect();

        if ($selectedStore) {
            // Buscar produtos da unidade selecionada
            $query = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->where('user_id', $selectedStore->id)
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc');

            // Filtro por categoria
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            // Busca por nome
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $products = $query->paginate(12);

            // Categorias com produtos disponíveis da unidade
            $categories = Category::where('is_active', true)
                ->whereHas('products', function ($productQuery) use ($selectedStore) {
                    $productQuery->where('is_active', true)
                        ->where('stock', '>', 0)
                        ->where('user_id', $selectedStore->id);
                })
                ->orderBy('display_order', 'asc')
                ->get();
        }

        return view('client.home', compact(
            'products',
            'categories',
            'stores',
            'selectedStore'
        ));
    }

    /**
     * Salva a unidade (loja) selecionada na sessão.
     */
    public function selectStore(Request $request): RedirectResponse
    {
        $request->validate([
            'store_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $store = User::where('id', $request->store_id)
            ->where('type', 'shop')
            ->where('is_active', true)
            ->first();

        if (!$store) {
            return back()->withErrors([
                'store_id' => 'Unidade inválida ou indisponível.',
            ]);
        }

        $request->session()->put('store_id', $store->id);

        return redirect()->route('client.home');
    }
}
