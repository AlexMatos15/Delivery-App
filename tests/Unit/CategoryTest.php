<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa criação de categoria com atributos corretos.
     */
    public function test_category_can_be_created(): void
    {
        $category = Category::factory()->create([
            'name' => 'Lanches',
            'slug' => 'lanches',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Lanches',
            'slug' => 'lanches',
        ]);
    }

    /**
     * Testa relacionamento: category tem muitos products.
     */
    public function test_category_has_many_products(): void
    {
        $category = Category::factory()->create();
        $shop = User::factory()->shop()->create();

        Product::factory()->count(3)->create([
            'category_id' => $category->id,
            'user_id' => $shop->id,
        ]);

        $this->assertCount(3, $category->products);
    }

    /**
     * Testa scope activeProducts retorna somente ativos.
     */
    public function test_active_products_scope(): void
    {
        $category = Category::factory()->create();
        $shop = User::factory()->shop()->create();

        Product::factory()->count(2)->create([
            'category_id' => $category->id,
            'user_id' => $shop->id,
            'is_active' => true,
        ]);

        Product::factory()->create([
            'category_id' => $category->id,
            'user_id' => $shop->id,
            'is_active' => false,
        ]);

        $this->assertCount(2, $category->activeProducts);
    }

    /**
     * Testa toggle de status ativo/inativo.
     */
    public function test_category_can_be_deactivated(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        
        $category->is_active = false;
        $category->save();

        $this->assertFalse($category->fresh()->is_active);
    }
}
