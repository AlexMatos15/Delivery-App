<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Product;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se isAdmin() retorna true para usuário tipo admin.
     */
    public function test_is_admin_returns_true_for_admin_type(): void
    {
        $user = User::factory()->admin()->create();

        $this->assertTrue($user->isAdmin());
    }

    /**
     * Testa se isAdmin() retorna true quando is_admin flag está ativo.
     */
    public function test_is_admin_returns_true_when_is_admin_flag_is_true(): void
    {
        $user = User::factory()->create([
            'type' => 'client',
            'is_admin' => true,
        ]);

        $this->assertTrue($user->isAdmin());
    }

    /**
     * Testa se isAdmin() retorna false para usuário comum.
     */
    public function test_is_admin_returns_false_for_regular_user(): void
    {
        $user = User::factory()->client()->create();

        $this->assertFalse($user->isAdmin());
    }

    /**
     * Testa se isShop() retorna true para usuário tipo shop.
     */
    public function test_is_shop_returns_true_for_shop_type(): void
    {
        $user = User::factory()->shop()->create();

        $this->assertTrue($user->isShop());
        $this->assertTrue($user->isLoja());
    }

    /**
     * Testa se isShop() retorna false para não-shop.
     */
    public function test_is_shop_returns_false_for_non_shop(): void
    {
        $user = User::factory()->client()->create();

        $this->assertFalse($user->isShop());
    }

    /**
     * Testa se isClient() retorna true para usuário tipo client.
     */
    public function test_is_client_returns_true_for_client_type(): void
    {
        $user = User::factory()->client()->create();

        $this->assertTrue($user->isClient());
        $this->assertTrue($user->isCliente());
    }

    /**
     * Testa se isClient() retorna false para não-client.
     */
    public function test_is_client_returns_false_for_non_client(): void
    {
        $user = User::factory()->shop()->create();

        $this->assertFalse($user->isClient());
    }

    /**
     * Testa se isActive() retorna true para usuário ativo.
     */
    public function test_is_active_returns_true_for_active_user(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->assertTrue($user->isActive());
    }

    /**
     * Testa se isActive() retorna false para usuário inativo.
     */
    public function test_is_active_returns_false_for_inactive_user(): void
    {
        $user = User::factory()->inactive()->create();

        $this->assertFalse($user->isActive());
    }

    /**
     * Testa relacionamento: user tem muitos products.
     */
    public function test_user_has_many_products(): void
    {
        $shop = User::factory()->shop()->create();
        $category = \App\Models\Category::factory()->create();

        Product::factory()->count(3)->create([
            'user_id' => $shop->id,
            'category_id' => $category->id,
        ]);

        $this->assertCount(3, $shop->products);
    }

    /**
     * Testa relacionamento: user tem muitos addresses.
     */
    public function test_user_has_many_addresses(): void
    {
        $user = User::factory()->client()->create();

        Address::factory()->count(2)->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->addresses);
    }

    /**
     * Testa relacionamento: user tem muitos orders.
     */
    public function test_user_has_many_orders(): void
    {
        $client = User::factory()->client()->create();
        $shop = User::factory()->shop()->create();
        $address = Address::factory()->create(['user_id' => $client->id]);

        Order::factory()->count(2)->create([
            'user_id' => $client->id,
            'shop_id' => $shop->id,
            'address_id' => $address->id,
        ]);

        $this->assertCount(2, $client->orders);
    }

    /**
     * Testa relacionamento: shop tem muitos shopOrders.
     */
    public function test_shop_has_many_shop_orders(): void
    {
        $client = User::factory()->client()->create();
        $shop = User::factory()->shop()->create();
        $address = Address::factory()->create(['user_id' => $client->id]);

        Order::factory()->count(2)->create([
            'user_id' => $client->id,
            'shop_id' => $shop->id,
            'address_id' => $address->id,
        ]);

        $this->assertCount(2, $shop->shopOrders);
    }
}
