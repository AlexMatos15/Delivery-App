<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $shop;
    private User $client;
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shop = User::factory()->shop()->create();
        $this->client = User::factory()->client()->create();
        $category = Category::factory()->create();
        $address = Address::factory()->create(['user_id' => $this->client->id]);

        $product = Product::factory()->create([
            'user_id' => $this->shop->id,
            'category_id' => $category->id,
        ]);

        $this->order = Order::factory()->create([
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'address_id' => $address->id,
            'status' => 'pending',
        ]);

        OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_id' => $product->id,
        ]);
    }

    /**
     * Testa que loja pode confirmar pedido pendente.
     */
    public function test_shop_can_confirm_pending_order(): void
    {
        $response = $this->actingAs($this->shop)
            ->patch(route('loja.orders.update-status', $this->order), [
                'status' => 'confirmed',
            ]);

        $response->assertRedirect();
        $this->assertEquals('confirmed', $this->order->fresh()->status);
    }

    /**
     * Testa que loja pode avançar para preparing.
     */
    public function test_shop_can_set_preparing(): void
    {
        $this->order->update(['status' => 'confirmed']);

        $response = $this->actingAs($this->shop)
            ->patch(route('loja.orders.update-status', $this->order), [
                'status' => 'preparing',
            ]);

        $response->assertRedirect();
        $this->assertEquals('preparing', $this->order->fresh()->status);
    }

    /**
     * Testa que loja pode marcar como saiu para entrega.
     */
    public function test_shop_can_set_out_for_delivery(): void
    {
        $this->order->update(['status' => 'preparing']);

        $response = $this->actingAs($this->shop)
            ->patch(route('loja.orders.update-status', $this->order), [
                'status' => 'out_for_delivery',
            ]);

        $response->assertRedirect();
        $this->assertEquals('out_for_delivery', $this->order->fresh()->status);
    }

    /**
     * Testa que loja pode marcar como entregue.
     */
    public function test_shop_can_set_delivered(): void
    {
        $this->order->update(['status' => 'out_for_delivery']);

        $response = $this->actingAs($this->shop)
            ->patch(route('loja.orders.update-status', $this->order), [
                'status' => 'delivered',
            ]);

        $response->assertRedirect();
        $this->assertEquals('delivered', $this->order->fresh()->status);
    }

    /**
     * Testa que loja pode visualizar pedido.
     */
    public function test_shop_can_view_order_details(): void
    {
        $response = $this->actingAs($this->shop)
            ->get(route('loja.orders.show', $this->order));

        $response->assertStatus(200);
    }
}
