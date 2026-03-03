<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa getCurrentPrice() sem preço promocional.
     */
    public function test_get_current_price_returns_regular_price(): void
    {
        $product = Product::factory()->create([
            'price' => 25.00,
            'promotional_price' => null,
        ]);

        $this->assertEquals(25.00, $product->getCurrentPrice());
    }

    /**
     * Testa getCurrentPrice() com preço promocional.
     */
    public function test_get_current_price_returns_promotional_when_available(): void
    {
        $product = Product::factory()->create([
            'price' => 25.00,
            'promotional_price' => 18.00,
        ]);

        $this->assertEquals(18.00, $product->getCurrentPrice());
    }

    /**
     * Testa isOnSale() quando produto está em promoção.
     */
    public function test_is_on_sale_returns_true_when_promotional(): void
    {
        $product = Product::factory()->create([
            'price' => 50.00,
            'promotional_price' => 35.00,
        ]);

        $this->assertTrue($product->isOnSale());
    }

    /**
     * Testa isOnSale() quando produto NÃO está em promoção.
     */
    public function test_is_on_sale_returns_false_without_promotional(): void
    {
        $product = Product::factory()->create([
            'price' => 50.00,
            'promotional_price' => null,
        ]);

        $this->assertFalse($product->isOnSale());
    }

    /**
     * Testa inStock() com estoque disponível.
     */
    public function test_in_stock_returns_true_when_stock_available(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $this->assertTrue($product->inStock());
    }

    /**
     * Testa inStock() sem estoque.
     */
    public function test_in_stock_returns_false_when_out_of_stock(): void
    {
        $product = Product::factory()->outOfStock()->create();

        $this->assertFalse($product->inStock());
    }

    /**
     * Testa geração automática de slug único.
     */
    public function test_slug_is_auto_generated_on_create(): void
    {
        $shop = User::factory()->shop()->create();
        $category = Category::factory()->create();

        $product = Product::create([
            'user_id' => $shop->id,
            'category_id' => $category->id,
            'name' => 'Hambúrguer Artesanal',
            'price' => 25.00,
            'stock' => 10,
        ]);

        $this->assertEquals('hamburguer-artesanal', $product->slug);
    }

    /**
     * Testa que slugs duplicados geram sufixo incremental.
     */
    public function test_duplicate_slug_gets_incremental_suffix(): void
    {
        $shop = User::factory()->shop()->create();
        $category = Category::factory()->create();

        $product1 = Product::create([
            'user_id' => $shop->id,
            'category_id' => $category->id,
            'name' => 'Pizza Margherita',
            'price' => 30.00,
            'stock' => 5,
        ]);

        $product2 = Product::create([
            'user_id' => $shop->id,
            'category_id' => $category->id,
            'name' => 'Pizza Margherita',
            'price' => 35.00,
            'stock' => 3,
        ]);

        $this->assertEquals('pizza-margherita', $product1->slug);
        $this->assertEquals('pizza-margherita-2', $product2->slug);
    }

    /**
     * Testa relacionamento: product pertence a um user (loja).
     */
    public function test_product_belongs_to_shop(): void
    {
        $shop = User::factory()->shop()->create();
        $product = Product::factory()->create(['user_id' => $shop->id]);

        $this->assertEquals($shop->id, $product->user->id);
        $this->assertEquals($shop->id, $product->shop->id);
    }

    /**
     * Testa relacionamento: product pertence a uma category.
     */
    public function test_product_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertEquals($category->id, $product->category->id);
    }
}
