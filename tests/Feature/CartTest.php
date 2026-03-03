<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private User $client;
    private User $shop;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shop = User::factory()->shop()->create();
        $this->client = User::factory()->client()->create();
        $category = Category::factory()->create();

        $this->product = Product::factory()->create([
            'user_id' => $this->shop->id,
            'category_id' => $category->id,
            'price' => 25.00,
            'stock' => 10,
            'is_active' => true,
        ]);
    }

    /**
     * Testa adicionar produto ao carrinho.
     */
    public function test_client_can_add_product_to_cart(): void
    {
        $response = $this->actingAs($this->client)
            ->post(route('cart.add', $this->product), [
                'quantity' => 2,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('cart');
    }

    /**
     * Testa que não pode adicionar mais que o estoque.
     */
    public function test_cannot_add_more_than_stock(): void
    {
        $response = $this->actingAs($this->client)
            ->post(route('cart.add', $this->product), [
                'quantity' => 999,
            ]);

        $response->assertSessionHasErrors('quantity');
    }

    /**
     * Testa atualizar quantidade no carrinho.
     */
    public function test_client_can_update_cart_quantity(): void
    {
        // Adicionar primeiro
        $this->actingAs($this->client)
            ->post(route('cart.add', $this->product), ['quantity' => 1]);

        // Atualizar
        $response = $this->actingAs($this->client)
            ->patch(route('cart.update', $this->product->id), [
                'quantity' => 3,
            ]);

        $response->assertRedirect();
    }

    /**
     * Testa remover produto do carrinho.
     */
    public function test_client_can_remove_product_from_cart(): void
    {
        // Adicionar primeiro
        $this->actingAs($this->client)
            ->post(route('cart.add', $this->product), ['quantity' => 1]);

        // Remover
        $response = $this->actingAs($this->client)
            ->delete(route('cart.remove', $this->product->id));

        $response->assertRedirect();
    }

    /**
     * Testa limpar carrinho completamente.
     */
    public function test_client_can_clear_cart(): void
    {
        // Adicionar primeiro
        $this->actingAs($this->client)
            ->post(route('cart.add', $this->product), ['quantity' => 1]);

        // Limpar
        $response = $this->actingAs($this->client)
            ->delete(route('cart.clear'));

        $response->assertRedirect();
    }

    /**
     * Testa que visitante não pode acessar carrinho.
     */
    public function test_guest_cannot_access_cart(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertRedirect('/login');
    }

    /**
     * Testa que shop não pode acessar carrinho.
     */
    public function test_shop_cannot_add_to_cart(): void
    {
        $response = $this->actingAs($this->shop)
            ->post(route('cart.add', $this->product), ['quantity' => 1]);

        $response->assertStatus(403);
    }
}
