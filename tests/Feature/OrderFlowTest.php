<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private User $client;
    private User $shop;
    private Product $product;
    private Address $address;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shop = User::factory()->shop()->create();
        $this->client = User::factory()->client()->create();
        $category = Category::factory()->create();

        $this->product = Product::factory()->create([
            'user_id' => $this->shop->id,
            'category_id' => $category->id,
            'name' => 'Produto Teste',
            'price' => 25.00,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->address = Address::factory()->default()->create([
            'user_id' => $this->client->id,
        ]);
    }

    /**
     * Testa criação de pedido via checkout.
     */
    public function test_client_can_create_order(): void
    {
        // Simular carrinho na sessão
        $cart = [
            $this->product->id => [
                'product_id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 2,
                'image' => null,
                'shop_id' => $this->shop->id,
            ],
        ];

        $response = $this->actingAs($this->client)
            ->withSession([
                'cart' => $cart,
                'cart_store_id' => $this->shop->id,
            ])
            ->post(route('orders.store'), [
                'address_id' => $this->address->id,
                'payment_method' => 'pix',
                'notes' => 'Sem cebola',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Testa que estoque é decrementado ao criar pedido.
     */
    public function test_stock_decremented_on_order_creation(): void
    {
        $initialStock = $this->product->stock;

        $cart = [
            $this->product->id => [
                'product_id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 3,
                'image' => null,
                'shop_id' => $this->shop->id,
            ],
        ];

        $this->actingAs($this->client)
            ->withSession([
                'cart' => $cart,
                'cart_store_id' => $this->shop->id,
            ])
            ->post(route('orders.store'), [
                'address_id' => $this->address->id,
                'payment_method' => 'pix',
            ]);

        $this->assertEquals($initialStock - 3, $this->product->fresh()->stock);
    }

    /**
     * Testa cancelamento de pedido.
     */
    public function test_client_can_cancel_pending_order(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'address_id' => $this->address->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->client)
            ->patch(route('orders.cancel', $order));

        $response->assertRedirect();
        $this->assertEquals('cancelled', $order->fresh()->status);
    }

    /**
     * Testa que estoque é restaurado ao cancelar pedido.
     */
    public function test_stock_restored_on_order_cancellation(): void
    {
        // Decrementar estoque simulando compra
        $this->product->decrement('stock', 2);
        $this->assertEquals(8, $this->product->fresh()->stock);

        $order = Order::factory()->create([
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'address_id' => $this->address->id,
            'status' => 'pending',
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $this->actingAs($this->client)
            ->patch(route('orders.cancel', $order));

        $this->assertEquals(10, $this->product->fresh()->stock);
    }

    /**
     * Testa que NÃO pode cancelar pedido 'preparing'.
     */
    public function test_cannot_cancel_preparing_order(): void
    {
        $order = Order::factory()->preparing()->create([
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'address_id' => $this->address->id,
        ]);

        $response = $this->actingAs($this->client)
            ->patch(route('orders.cancel', $order));

        $this->assertNotEquals('cancelled', $order->fresh()->status);
    }

    /**
     * Testa que client pode ver seus pedidos.
     */
    public function test_client_can_view_order_history(): void
    {
        Order::factory()->count(3)->create([
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'address_id' => $this->address->id,
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('orders.index'));

        $response->assertStatus(200);
    }

    /**
     * Testa que client pode ver detalhes do pedido.
     */
    public function test_client_can_view_order_details(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'address_id' => $this->address->id,
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('orders.show', $order));

        $response->assertStatus(200);
    }

    /**
     * Testa que client NÃO pode ver pedido de outro usuário.
     */
    public function test_client_cannot_view_other_users_order(): void
    {
        $otherClient = User::factory()->client()->create();
        $otherAddress = Address::factory()->create(['user_id' => $otherClient->id]);

        $order = Order::factory()->create([
            'user_id' => $otherClient->id,
            'shop_id' => $this->shop->id,
            'address_id' => $otherAddress->id,
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('orders.show', $order));

        $response->assertStatus(403);
    }
}
