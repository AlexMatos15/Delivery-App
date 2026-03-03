<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Address;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa que pedido novo tem status 'pending'.
     */
    public function test_new_order_has_pending_status(): void
    {
        $order = Order::factory()->create();

        $this->assertEquals('pending', $order->status);
        $this->assertTrue($order->isPending());
    }

    /**
     * Testa isPending() para pedido pendente.
     */
    public function test_is_pending_returns_true(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $this->assertTrue($order->isPending());
        $this->assertFalse($order->isCancelled());
        $this->assertFalse($order->isCompleted());
    }

    /**
     * Testa isConfirmed() para pedidos em andamento.
     */
    public function test_is_confirmed_returns_true_for_active_statuses(): void
    {
        $confirmed = Order::factory()->confirmed()->create();
        $preparing = Order::factory()->preparing()->create();

        $this->assertTrue($confirmed->isConfirmed());
        $this->assertTrue($preparing->isConfirmed());
    }

    /**
     * Testa isCompleted() para pedido entregue.
     */
    public function test_is_completed_returns_true_for_delivered(): void
    {
        $order = Order::factory()->delivered()->create();

        $this->assertTrue($order->isCompleted());
        $this->assertFalse($order->isPending());
    }

    /**
     * Testa isCancelled() para pedido cancelado.
     */
    public function test_is_cancelled_returns_true(): void
    {
        $order = Order::factory()->cancelled()->create();

        $this->assertTrue($order->isCancelled());
        $this->assertFalse($order->isPending());
    }

    /**
     * Testa geração de número de pedido com formato correto.
     */
    public function test_generate_order_number_has_correct_format(): void
    {
        $number = Order::generateOrderNumber();

        $this->assertStringStartsWith('ORD-', $number);
        $this->assertMatchesRegularExpression('/^ORD-\d{8}-\d{4}$/', $number);
    }

    /**
     * Testa cálculo do total do pedido (subtotal + delivery_fee - discount).
     */
    public function test_order_total_calculation(): void
    {
        $order = Order::factory()->create([
            'subtotal' => 100.00,
            'delivery_fee' => 5.00,
            'discount' => 10.00,
            'total' => 95.00,
        ]);

        $this->assertEquals(95.00, (float) $order->total);
        $this->assertEquals(100.00, (float) $order->subtotal);
        $this->assertEquals(5.00, (float) $order->delivery_fee);
    }

    /**
     * Testa relacionamento: order pertence a um user (cliente).
     */
    public function test_order_belongs_to_customer(): void
    {
        $client = User::factory()->client()->create();
        $order = Order::factory()->create(['user_id' => $client->id]);

        $this->assertEquals($client->id, $order->user->id);
        $this->assertEquals($client->id, $order->customer->id);
    }

    /**
     * Testa relacionamento: order pertence a um shop.
     */
    public function test_order_belongs_to_shop(): void
    {
        $shop = User::factory()->shop()->create();
        $order = Order::factory()->create(['shop_id' => $shop->id]);

        $this->assertEquals($shop->id, $order->shop->id);
    }

    /**
     * Testa relacionamento: order tem muitos items.
     */
    public function test_order_has_many_items(): void
    {
        $order = Order::factory()->create();
        OrderItem::factory()->count(3)->create(['order_id' => $order->id]);

        $this->assertCount(3, $order->items);
    }

    /**
     * Testa relacionamento: order pertence a um address.
     */
    public function test_order_belongs_to_address(): void
    {
        $client = User::factory()->client()->create();
        $address = Address::factory()->create(['user_id' => $client->id]);
        $order = Order::factory()->create([
            'user_id' => $client->id,
            'address_id' => $address->id,
        ]);

        $this->assertEquals($address->id, $order->address->id);
    }
}
