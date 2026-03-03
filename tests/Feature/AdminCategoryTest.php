<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    /**
     * Testa que admin pode listar categorias.
     */
    public function test_admin_can_list_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.categories.index'));

        $response->assertStatus(200);
    }

    /**
     * Testa que admin pode criar categoria.
     */
    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Bebidas',
                'description' => 'Bebidas geladas e quentes',
                'is_active' => true,
                'display_order' => 1,
            ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Bebidas',
            'slug' => 'bebidas',
        ]);
    }

    /**
     * Testa que admin pode editar categoria.
     */
    public function test_admin_can_update_category(): void
    {
        $category = Category::factory()->create(['name' => 'Lanches']);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'Lanches Especiais',
                'description' => 'Lanches premium',
                'is_active' => true,
                'display_order' => 0,
            ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Lanches Especiais',
        ]);
    }

    /**
     * Testa que admin pode ativar/desativar categoria.
     */
    public function test_admin_can_toggle_category_status(): void
    {
        $category = Category::factory()->create(['is_active' => true]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.categories.toggle', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertFalse($category->fresh()->is_active);
    }

    /**
     * Testa que admin pode excluir categoria.
     */
    public function test_admin_can_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /**
     * Testa validação: nome é obrigatório.
     */
    public function test_category_name_is_required(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), [
                'name' => '',
            ]);

        $response->assertSessionHasErrors('name');
    }
}
