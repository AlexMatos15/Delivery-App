<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

/**
 * CheckoutController
 * 
 * Controlador para o processo de checkout
 * Responsável por transformar o carrinho em pedido real
 * 
 * REGRAS CRÍTICAS:
 * - Carrinho deve ter itens
 * - Cliente deve ter endereço para entrega
 * - Estoque deve ser suficiente
 * - Carrinho deve ter apenas itens de UMA loja
 * - Pedido é criado com status 'pending' e pagamento pendente
 */
class CheckoutController extends Controller
{
    /**
     * Exibe página de checkout
     */
    public function index()
    {
        // Verificar se usuário está autenticado
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Faça login para fazer seu pedido');
        }

        // Verificar se tem carrinho com itens
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Seu carrinho está vazio');
        }

        $user = auth()->user();
        
        // Verificar se usuário tem endereços
        $addresses = $user->addresses()->get();
        if ($addresses->isEmpty()) {
            return redirect()->route('addresses.create')
                ->with('error', 'Você precisa adicionar um endereço antes de fazer pedidos');
        }

        // Preparar dados do carrinho para exibição
        $cartItems = $this->prepareCartItems($cart);
        $subtotal = $this->calculateSubtotal($cartItems);
        $deliveryFee = 5.00; // Taxa fixa de entrega
        $total = $subtotal + $deliveryFee;

        // Obter endereço padrão
        $defaultAddress = $user->addresses()->where('is_default', true)->first();

        return view('client.checkout', compact(
            'cartItems',
            'subtotal',
            'deliveryFee',
            'total',
            'addresses',
            'defaultAddress'
        ));
    }

    /**
     * Processa o checkout e cria o pedido
     */
    public function store(Request $request)
    {
        // Validar input
        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:credit_card,debit_card,pix,cash',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        $cart = Session::get('cart', []);

        // Verificar se tem carrinho
        if (empty($cart)) {
            return back()->with('error', 'Seu carrinho está vazio');
        }

        // Verificar se o endereço pertence ao usuário
        $address = $user->addresses()->findOrFail($validated['address_id']);

        // Obter a loja do carrinho
        $cartStoreId = Session::get('cart_store_id');
        if (!$cartStoreId) {
            return back()->with('error', 'Erro ao processar carrinho. Tente novamente.');
        }

        // TRANSAÇÃO DATABASE: garantir que tudo seja salvo atomicamente
        try {
            DB::beginTransaction();

            // Validar e reservar estoque para todos os itens
            $orderItems = [];
            foreach ($cart as $productId => $item) {
                $product = Product::findOrFail($productId);

                // Verificar se produto ainda está ativo
                if (!$product->is_active) {
                    throw new \Exception("Produto '{$product->name}' não está mais disponível");
                }

                // Verificar estoque
                if ($product->stock < $item['quantity']) {
                    throw new \Exception(
                        "Estoque insuficiente para '{$product->name}'. " .
                        "Disponível: {$product->stock}"
                    );
                }

                $orderItems[$productId] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                ];
            }

            // Calcular totais
            $subtotal = 0;
            foreach ($orderItems as $productId => $itemData) {
                $subtotal += $itemData['unit_price'] * $itemData['quantity'];
            }
            $deliveryFee = 5.00;
            $total = $subtotal + $deliveryFee;

            // Criar o pedido
            $order = Order::create([
                'user_id' => $user->id,
                'shop_user_id' => $cartStoreId,
                'address_id' => $address->id,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Criar itens do pedido e decrementar estoque
            foreach ($orderItems as $productId => $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_name' => $itemData['product']->name,
                    'product_price' => $itemData['unit_price'],
                    'quantity' => $itemData['quantity'],
                ]);

                // DECREMENTAR ESTOQUE
                $itemData['product']->decrement('stock', $itemData['quantity']);
            }

            // Limpar carrinho da sessão
            Session::forget('cart');
            Session::forget('cart_store_id');

            // Commit da transação
            DB::commit();

            // Redirecionar para confirmação com sucesso
            return redirect()->route('client.order-confirmation', ['order' => $order->id])
                ->with('status', 'Pedido criado com sucesso!');

        } catch (\Exception $e) {
            // Rollback automático no begin/commit
            DB::rollBack();

            return back()
                ->with('error', 'Erro ao criar pedido: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Exibe confirmação do pedido criado
     */
    public function confirmation(Order $order)
    {
        // Verificar se o pedido pertence ao usuário autenticado
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $order->load('items.product', 'address', 'shop');

        return view('client.order-confirmation', compact('order'));
    }

    /**
     * Prepara itens do carrinho para exibição
     */
    private function prepareCartItems(array $cart)
    {
        $items = [];
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $items[] = [
                    'product_id' => $productId,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'image' => $item['image'] ?? null,
                    'subtotal' => $item['price'] * $item['quantity'],
                ];
            }
        }
        return $items;
    }

    /**
     * Calcula subtotal do carrinho
     */
    private function calculateSubtotal(array $cartItems)
    {
        return array_sum(array_map(fn($item) => $item['subtotal'], $cartItems));
    }

    /**
     * Gera número único do pedido
     * Formato: ORD-XXXXXXXX (8 caracteres aleatórios)
     */
    private function generateOrderNumber()
    {
        do {
            $number = 'ORD-' . strtoupper(substr(md5(time() . rand()), 0, 8));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
