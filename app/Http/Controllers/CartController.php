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
        
        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        if (!$product->is_active || $product->stock < $request->quantity) {
            return back()->with('error', __('Product not available or insufficient stock.'));
        }

        $cart = $this->getCart();
        $productId = $product->id;

        if (isset($cart[$productId])) {
            // Update quantity if product already in cart
            $newQuantity = $cart[$productId]['quantity'] + $request->quantity;
            
            if ($newQuantity > $product->stock) {
                return back()->with('error', __('Insufficient stock. Available: :stock', ['stock' => $product->stock]));
            }
            
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            // Add new product to cart
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->getCurrentPrice(),
                'quantity' => $request->quantity,
                'image' => $product->image,
                'shop_id' => $product->user_id,
            ];
        }

        $this->saveCart($cart);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Product added to cart!'),
                'cart_count' => $this->getCartCount(),
            ]);
        }

        return back()->with('status', __('Product added to cart!'));
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
            return back()->with('error', __('Product not found in cart.'));
        }

        $product = Product::find($productId);
        
        if (!$product || $request->quantity > $product->stock) {
            return back()->with('error', __('Insufficient stock. Available: :stock', ['stock' => $product->stock ?? 0]));
        }

        $cart[$productId]['quantity'] = $request->quantity;
        $this->saveCart($cart);

        if ($request->expectsJson()) {
            $total = $this->calculateTotal($cart);
            return response()->json([
                'success' => true,
                'message' => __('Cart updated!'),
                'cart_count' => $this->getCartCount(),
                'total' => $total,
                'item_subtotal' => $cart[$productId]['price'] * $cart[$productId]['quantity'],
            ]);
        }

        return back()->with('status', __('Cart updated!'));
    }

    /**
     * Remove product from cart.
     */
    public function remove($productId)
    {
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->saveCart($cart);
        }

        if (request()->expectsJson()) {
            $total = $this->calculateTotal($cart);
            return response()->json([
                'success' => true,
                'message' => __('Product removed from cart!'),
                'cart_count' => $this->getCartCount(),
                'total' => $total,
            ]);
        }

        return back()->with('status', __('Product removed from cart!'));
    }

    /**
     * Clear all items from cart.
     */
    public function clear()
    {
        Session::forget('cart');
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Cart cleared!'),
            ]);
        }

        return back()->with('status', __('Cart cleared!'));
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
     * Save cart to session.
     */
    protected function saveCart(array $cart): void
    {
        Session::put('cart', $cart);
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
