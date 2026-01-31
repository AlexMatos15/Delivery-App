<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Display user's order history.
     */
    public function index()
    {
        $cliente = auth()->user();
        
        $orders = $cliente->orders()
            ->with(['items.product', 'shop'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('cliente', 'orders'));
    }

    /**
     * Show checkout page.
     */
    public function checkout()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('Your cart is empty.'));
        }

        // Get user addresses
        $addresses = auth()->user()->addresses;
        $defaultAddress = $addresses->where('is_default', true)->first() ?? $addresses->first();

        // Calculate totals
        $subtotal = $this->calculateSubtotal($cart);
        $deliveryFee = 5.00; // Fixed for now
        $total = $subtotal + $deliveryFee;

        return view('orders.checkout', compact('cart', 'addresses', 'defaultAddress', 'subtotal', 'deliveryFee', 'total'));
    }

    /**
     * Create order from cart.
     */
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:credit_card,debit_card,pix,cash',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('Your cart is empty.'));
        }

        // Verify address belongs to user
        $address = auth()->user()->addresses()->findOrFail($request->address_id);

        try {
            DB::beginTransaction();

            // Group cart items by shop
            $itemsByShop = $this->groupCartByShop($cart);

            $createdOrders = [];

            foreach ($itemsByShop as $shopId => $items) {
                // Calculate order total
                $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $items));
                $deliveryFee = 5.00;
                $total = $subtotal + $deliveryFee;

                // Create order
                $order = Order::create([
                    'order_number' => $this->generateOrderNumber(),
                    'user_id' => auth()->id(),
                    'shop_id' => $shopId,
                    'address_id' => $address->id,
                    'delivery_address' => $this->formatAddress($address),
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'total' => $total,
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                    'notes' => $request->notes,
                ]);

                // Create order items
                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'product_name' => $item['name'],
                        'product_price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    // Update product stock
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock', $item['quantity']);
                    }
                }

                $createdOrders[] = $order;
            }

            DB::commit();

            // Clear cart
            Session::forget('cart');

            if (count($createdOrders) === 1) {
                return redirect()->route('orders.show', $createdOrders[0])
                    ->with('status', __('Order placed successfully!'));
            }

            return redirect()->route('orders.index')
                ->with('status', __(':count orders placed successfully!', ['count' => count($createdOrders)]));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', __('Error creating order. Please try again.'));
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Verify order belongs to user or user is admin
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $order->load(['items.product', 'shop', 'user']);

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel an order.
     */
    public function cancel(Order $order)
    {
        // Verify order belongs to user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Can only cancel pending or confirmed orders
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return back()->with('error', __('This order cannot be cancelled.'));
        }

        try {
            DB::beginTransaction();

            $order->update(['status' => 'cancelled']);

            // Restore product stock
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            DB::commit();

            return back()->with('status', __('Order cancelled successfully.'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', __('Error cancelling order. Please try again.'));
        }
    }

    /**
     * Generate unique order number.
     */
    protected function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . strtoupper(substr(uniqid(), -8));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Group cart items by shop.
     */
    protected function groupCartByShop(array $cart): array
    {
        $grouped = [];

        foreach ($cart as $item) {
            $shopId = $item['shop_id'];
            
            if (!isset($grouped[$shopId])) {
                $grouped[$shopId] = [];
            }
            
            $grouped[$shopId][] = $item;
        }

        return $grouped;
    }

    /**
     * Calculate cart subtotal.
     */
    protected function calculateSubtotal(array $cart): float
    {
        return array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
    }

    /**
     * Format address for storage.
     */
    protected function formatAddress(Address $address): string
    {
        return sprintf(
            "%s, %s - %s, %s - %s, %s",
            $address->street,
            $address->number,
            $address->neighborhood,
            $address->city,
            $address->state,
            $address->zip_code
        );
    }
}
