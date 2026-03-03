<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = $this->getCart();
        $total = $this->calculateTotal($cart);
        
        return view('client.cart', compact('cart', 'total'));
    }

    /**
     * Add a product to the cart.
     * 
     * REGRA DE NEGÓCIO CRÍTICA:
     * Um carrinho pertence a UMA ÚNICA LOJA.
     * Se o cliente tentar adicionar um produto de outra loja, é bloqueado com erro claro.
     */
    public function add(Request $request, Product $product)
    {
        // Validar quantidade
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        // Verificar se produto está ativo e com estoque
        if (!$product->is_active || $product->stock < $request->quantity) {
            return back()->with('error', 'Produto indisponível ou estoque insuficiente.');
        }

        $cart = $this->getCart();
        $cartStoreId = $this->getCartStoreId();
        
        /**
         * VALIDAÇÃO DE LOJA:
         * Se o carrinho já tem uma loja associada, verificar se é a mesma do produto
         */
        if ($cartStoreId !== null && $cartStoreId !== $product->user_id) {
            // Carrinho contém produtos de outra loja
            return back()->with('error', 
                'Seu carrinho contém itens de outra loja. ' .
                'Finalize a compra ou limpe o carrinho antes de adicionar produtos de outro vendedor.'
            );
        }

        $productId = $product->id;

        if (isset($cart[$productId])) {
            // Atualizar quantidade se produto já está no carrinho
            $newQuantity = $cart[$productId]['quantity'] + $request->quantity;
            
            if ($newQuantity > $product->stock) {
                return back()->with('error', 
                    'Estoque insuficiente. Disponível: ' . $product->stock
                );
            }
            
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            // Adicionar novo produto ao carrinho
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->getCurrentPrice(),
                'quantity' => $request->quantity,
                'image' => $product->image,
                'shop_id' => $product->user_id,
            ];
        }

        // Salvar carrinho com a loja associada
        $this->saveCart($cart, $product->user_id);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produto adicionado ao carrinho!',
                'cart_count' => $this->getCartCount(),
            ]);
        }

        return back()->with('status', 'Produto adicionado ao carrinho!');
    }

    /**
     * Update product quantity in cart.
     */
    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getCart();

        if (!isset($cart[$productId])) {
            return back()->with('error', 'Produto não encontrado no carrinho.');
        }

        $product = Product::find($productId);
        
        if (!$product || $request->quantity > $product->stock) {
            return back()->with('error', 
                'Estoque insuficiente. Disponível: ' . ($product->stock ?? 0)
            );
        }

        $cart[$productId]['quantity'] = $request->quantity;
        $this->saveCart($cart, $this->getCartStoreId());

        if ($request->expectsJson()) {
            $total = $this->calculateTotal($cart);
            return response()->json([
                'success' => true,
                'message' => 'Carrinho atualizado!',
                'cart_count' => $this->getCartCount(),
                'total' => $total,
                'item_subtotal' => $cart[$productId]['price'] * $cart[$productId]['quantity'],
            ]);
        }

        return back()->with('status', 'Carrinho atualizado!');
    }

    /**
     * Remove product from cart.
     */
    public function remove($productId)
    {
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->saveCart($cart, $this->getCartStoreId());
        }

        if (request()->expectsJson()) {
            $total = $this->calculateTotal($cart);
            return response()->json([
                'success' => true,
                'message' => 'Produto removido do carrinho!',
                'cart_count' => $this->getCartCount(),
                'total' => $total,
            ]);
        }

        return back()->with('status', 'Produto removido do carrinho!');
    }

    /**
     * Clear all items from cart.
     */
    public function clear()
    {
        Session::forget('cart');
        Session::forget('cart_store_id');
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Carrinho limpo!',
            ]);
        }

        return back()->with('status', 'Carrinho limpo!');
    }

    /**
     * Get cart count for badge display.
     */
    public function count()
    {
        return response()->json([
            'count' => $this->getCartCount(),
        ]);
    }

    /**
     * Get cart from session.
     */
    protected function getCart(): array
    {
        return Session::get('cart', []);
    }

    /**
     * Get store_id associated with current cart.
     * 
     * Retorna null se o carrinho está vazio.
     * Caso contrário, retorna o ID da loja.
     */
    protected function getCartStoreId(): ?int
    {
        return Session::get('cart_store_id');
    }

    /**
     * Save cart to session with store association.
     * 
     * @param array $cart Array com produtos do carrinho
     * @param int|null $storeId ID da loja (user_id do vendedor)
     */
    protected function saveCart(array $cart, ?int $storeId = null): void
    {
        Session::put('cart', $cart);
        
        // Se o carrinho não está vazio, associar à loja
        if (!empty($cart) && $storeId !== null) {
            Session::put('cart_store_id', $storeId);
        }
        
        // Se o carrinho ficou vazio, limpar a associação com a loja
        if (empty($cart)) {
            Session::forget('cart_store_id');
        }
    }

    /**
     * Calculate total items in cart.
     */
    protected function getCartCount(): int
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Calculate cart total price.
     */
    protected function calculateTotal(array $cart): float
    {
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }
}
