<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopProductTest extends TestCase
{
    use RefreshDatabase;

    private User $shop;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->shop = User::factory()->shop()->create();
        $this->category = Category::factory()->create();
    }

    /**
     * Testa que loja pode listar seus produtos.
     */
    public function test_shop_can_list_products(): void
    {
        Product::factory()->count(3)->create([
            'user_id' => $this->shop->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->shop)
            ->get(route('loja.products.index'));

        $response->assertStatus(200);
    }

    /**
     * Testa que loja pode criar produto.
     */
    public function test_shop_can_create_product(): void
    {
        $response = $this->actingAs($this->shop)
            ->post(route('loja.products.store'), [
                'name' => 'Pizza Calabresa',
                'category_id' => $this->category->id,
                'description' => 'Pizza de calabresa com cebola',
                'price' => 35.00,
                'stock' => 20,
                'is_active' => true,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'name' => 'Pizza Calabresa',
            'user_id' => $this->shop->id,
        ]);
    }

    /**
     * Testa que loja pode editar produto próprio.
     */
    public function test_shop_can_update_own_product(): void
    {
        $product = Product::factory()->create([
            'user_id' => $this->shop->id,
            'category_id' => $this->category->id,
            'name' => 'Hambúrguer',
        ]);

        $response = $this->actingAs($this->shop)
            ->put(route('loja.products.update', $product), [
                'name' => 'Hambúrguer Especial',
                'category_id' => $this->category->id,
                'price' => 30.00,
                'stock' => 15,
                'is_active' => true,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Hambúrguer Especial',
        ]);
    }

    /**
     * Testa que loja pode ativar/desativar produto.
     */
    public function test_shop_can_toggle_product_status(): void
    {
        $product = Product::factory()->create([
            'user_id' => $this->shop->id,
            'category_id' => $this->category->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->shop)
            ->patch(route('loja.products.toggle', $product));

        $response->assertRedirect();
        $this->assertFalse($product->fresh()->is_active);
    }

    /**
     * Testa validação: preço é obrigatório.
     */
    public function test_product_price_is_required(): void
    {
        $response = $this->actingAs($this->shop)
            ->post(route('loja.products.store'), [
                'name' => 'Produto Sem Preço',
                'category_id' => $this->category->id,
                'stock' => 10,
            ]);

        $response->assertSessionHasErrors('price');
    }
}
