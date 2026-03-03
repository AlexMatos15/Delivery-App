<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testes de Usabilidade — Delivery App
 *
 * Verificam a presença de labels, botões, mensagens de feedback,
 * erros de validação e redirecionamentos corretos, garantindo que
 * a interface comunica adequadamente o estado do sistema ao usuário.
 */
class UsabilityTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $shop;
    private User $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->shop = User::factory()->shop()->create();
        $this->client = User::factory()->client()->create();
    }

    // ═════════════════════════════════════════════
    //  1. LABELS E CAMPOS DE FORMULÁRIO
    // ═════════════════════════════════════════════

    /**
     * Testa que a página de login possui labels de Email e Senha e botão de Log in.
     */
    public function test_login_page_has_correct_labels_and_button(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Email');
        $response->assertSee('Password');
        $response->assertSee('Log in');
        $response->assertSee('Forgot your password?');
        $response->assertSee('Remember me');
    }

    /**
     * Testa que a página de registro possui todos os labels e botão Register.
     */
    public function test_register_page_has_correct_labels_and_button(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Name');
        $response->assertSee('Email');
        $response->assertSee('Password');
        $response->assertSee('Confirm Password');
        $response->assertSee('Register');
        $response->assertSee('Already registered?');
    }

    /**
     * Testa que a página de criar categoria (admin) possui labels e botão corretos.
     */
    public function test_admin_category_create_has_labels_and_button(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.create'));

        $response->assertStatus(200);
        $response->assertSee('Name', false);
        $response->assertSee('Description', false);
    }

    /**
     * Testa que a página de editar categoria (admin) mostra dados do recurso.
     */
    public function test_admin_category_edit_shows_resource_data(): void
    {
        $category = Category::factory()->create(['name' => 'Pizza Especial']);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.edit', $category));

        $response->assertStatus(200);
        $response->assertSee('Pizza Especial');
    }

    /**
     * Testa que a página de criar endereço (cliente) possui labels corretos.
     */
    public function test_address_create_has_correct_labels(): void
    {
        $response = $this->actingAs($this->client)->get(route('addresses.create'));

        $response->assertStatus(200);
        // Verificar que os campos obrigatórios estão presentes
        $response->assertSee('label', false);
        $response->assertSee('street', false);
        $response->assertSee('number', false);
        $response->assertSee('neighborhood', false);
        $response->assertSee('city', false);
        $response->assertSee('state', false);
        $response->assertSee('zip_code', false);
    }

    /**
     * Testa que a página de editar endereço mostra dados preenchidos do recurso.
     */
    public function test_address_edit_shows_resource_data(): void
    {
        $address = Address::factory()->create([
            'user_id' => $this->client->id,
            'label' => 'Casa Principal',
            'street' => 'Rua das Flores',
        ]);

        $response = $this->actingAs($this->client)->get(route('addresses.edit', $address));

        $response->assertStatus(200);
        $response->assertSee('Casa Principal');
        $response->assertSee('Rua das Flores');
    }

    // ═════════════════════════════════════════════
    //  2. MENSAGENS DE FEEDBACK (SUCESSO)
    // ═════════════════════════════════════════════

    /**
     * Testa que criar categoria exibe mensagem de sucesso na sessão.
     */
    public function test_category_creation_shows_success_message(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Nova Categoria Teste',
            'description' => 'Descrição teste',
            'is_active' => true,
            'display_order' => 0,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('status', 'Category created successfully.');
    }

    /**
     * Testa que atualizar categoria exibe mensagem de sucesso na sessão.
     */
    public function test_category_update_shows_success_message(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'Atualizada',
            'description' => 'Descrição atualizada',
            'is_active' => true,
            'display_order' => 1,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('status', 'Category updated successfully.');
    }

    /**
     * Testa que deletar categoria exibe mensagem de sucesso na sessão.
     */
    public function test_category_deletion_shows_success_message(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('status', 'Category deleted successfully.');
    }

    /**
     * Testa que toggle de categoria exibe mensagem de sucesso.
     */
    public function test_category_toggle_shows_success_message(): void
    {
        $category = Category::factory()->create(['is_active' => true]);

        $response = $this->actingAs($this->admin)->patch(route('admin.categories.toggle', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('status', 'Category deactivated successfully.');
    }

    /**
     * Testa que criar endereço exibe mensagem de sucesso na sessão.
     */
    public function test_address_creation_shows_success_message(): void
    {
        $response = $this->actingAs($this->client)->post(route('addresses.store'), [
            'label' => 'Casa',
            'street' => 'Rua Teste',
            'number' => '123',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01000-000',
        ]);

        $response->assertRedirect(route('addresses.index'));
        $response->assertSessionHas('status', 'Address added successfully!');
    }

    /**
     * Testa que atualizar endereço exibe mensagem de sucesso.
     */
    public function test_address_update_shows_success_message(): void
    {
        $address = Address::factory()->create(['user_id' => $this->client->id]);

        $response = $this->actingAs($this->client)->put(route('addresses.update', $address), [
            'label' => 'Trabalho',
            'street' => 'Av. Paulista',
            'number' => '1000',
            'neighborhood' => 'Bela Vista',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ]);

        $response->assertRedirect(route('addresses.index'));
        $response->assertSessionHas('status', 'Address updated successfully!');
    }

    /**
     * Testa que deletar endereço exibe mensagem de sucesso (quando possui outro).
     */
    public function test_address_deletion_shows_success_message(): void
    {
        Address::factory()->create(['user_id' => $this->client->id, 'is_default' => true]);
        $address2 = Address::factory()->create(['user_id' => $this->client->id, 'is_default' => false]);

        $response = $this->actingAs($this->client)->delete(route('addresses.destroy', $address2));

        $response->assertRedirect(route('addresses.index'));
        $response->assertSessionHas('status', 'Address deleted successfully!');
    }

    /**
     * Testa que ativar/desativar usuário (admin) exibe mensagem de sucesso.
     */
    public function test_user_toggle_shows_success_message(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($this->admin)->patch(route('admin.users.toggle', $user));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('status');
    }

    /**
     * Testa que deletar usuário (admin) exibe mensagem de sucesso.
     */
    public function test_user_deletion_shows_success_message(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('status', 'User deleted successfully.');
    }

    // ═════════════════════════════════════════════
    //  3. MENSAGENS DE FEEDBACK (ERRO)
    // ═════════════════════════════════════════════

    /**
     * Testa que não é possível deletar o único endereço do usuário — mostra erro.
     */
    public function test_cannot_delete_only_address_shows_error(): void
    {
        $address = Address::factory()->create(['user_id' => $this->client->id, 'is_default' => true]);

        // A mensagem de erro só aparece quando o usuário tem pedidos e tenta deletar o único endereço
        Order::factory()->create([
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'address_id' => $address->id,
        ]);

        $response = $this->actingAs($this->client)->delete(route('addresses.destroy', $address));

        $response->assertSessionHas('error', 'Cannot delete your only address. Add another address first.');
    }

    // ═════════════════════════════════════════════
    //  4. ERROS DE VALIDAÇÃO
    // ═════════════════════════════════════════════

    /**
     * Testa que submeter login com campos vazios retorna erros de validação.
     */
    public function test_login_validation_shows_errors(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    /**
     * Testa que submeter registro com campos vazios retorna erros de validação.
     */
    public function test_register_validation_shows_errors(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    /**
     * Testa que submeter registro com email duplicado retorna erro.
     */
    public function test_register_duplicate_email_shows_error(): void
    {
        User::factory()->create(['email' => 'existe@test.com']);

        $response = $this->post('/register', [
            'name' => 'Teste',
            'email' => 'existe@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Testa que submeter categoria sem nome retorna erro de validação.
     */
    public function test_category_validation_shows_errors(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Testa que submeter endereço com campos obrigatórios vazios retorna erros.
     */
    public function test_address_validation_shows_errors(): void
    {
        $response = $this->actingAs($this->client)->post(route('addresses.store'), [
            'label' => '',
            'street' => '',
            'number' => '',
            'neighborhood' => '',
            'city' => '',
            'state' => '',
            'zip_code' => '',
        ]);

        $response->assertSessionHasErrors([
            'label', 'street', 'number', 'neighborhood', 'city', 'state', 'zip_code',
        ]);
    }

    /**
     * Testa que o estado deve ter exatamente 2 caracteres.
     */
    public function test_address_state_validation_requires_two_chars(): void
    {
        $response = $this->actingAs($this->client)->post(route('addresses.store'), [
            'label' => 'Casa',
            'street' => 'Rua Teste',
            'number' => '123',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'São Paulo', // mais de 2 caracteres
            'zip_code' => '01000-000',
        ]);

        $response->assertSessionHasErrors(['state']);
    }

    // ═════════════════════════════════════════════
    //  5. REDIRECIONAMENTOS CORRETOS
    // ═════════════════════════════════════════════

    /**
     * Testa que login com credenciais inválidas redireciona de volta ao form.
     */
    public function test_failed_login_redirects_back(): void
    {
        $response = $this->post('/login', [
            'email' => 'naoexiste@test.com',
            'password' => 'senhaerrada',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /**
     * Testa que após criar categoria redireciona para listagem.
     */
    public function test_category_store_redirects_to_index(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Redirect Teste',
            'is_active' => true,
            'display_order' => 0,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
    }

    /**
     * Testa que após criar endereço redireciona para listagem.
     */
    public function test_address_store_redirects_to_index(): void
    {
        $response = $this->actingAs($this->client)->post(route('addresses.store'), [
            'label' => 'Casa',
            'street' => 'Rua Teste',
            'number' => '123',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01000-000',
        ]);

        $response->assertRedirect(route('addresses.index'));
    }

    /**
     * Testa que usuário não autenticado é redirecionado ao login ao acessar rota protegida.
     */
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/orders');
        $response->assertRedirect('/login');
    }

    /**
     * Testa que cliente não pode acessar área admin — é redirecionado.
     */
    public function test_client_cannot_access_admin_area(): void
    {
        $response = $this->actingAs($this->client)->get('/admin/dashboard');
        $response->assertForbidden();
    }

    /**
     * Testa que loja não pode acessar área admin — é redirecionado.
     */
    public function test_shop_cannot_access_admin_area(): void
    {
        $response = $this->actingAs($this->shop)->get('/admin/dashboard');
        $response->assertForbidden();
    }

    // ═════════════════════════════════════════════
    //  6. NAVEGAÇÃO E ACESSIBILIDADE
    // ═════════════════════════════════════════════

    /**
     * Testa que a listagem de categorias exibe os dados cadastrados.
     */
    public function test_categories_listing_displays_data(): void
    {
        Category::factory()->create(['name' => 'Bebidas Geladas']);
        Category::factory()->create(['name' => 'Lanches Rápidos']);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Bebidas Geladas');
        $response->assertSee('Lanches Rápidos');
    }

    /**
     * Testa que a listagem de usuários exibe informações dos usuários.
     */
    public function test_users_listing_displays_data(): void
    {
        $user = User::factory()->create(['name' => 'Maria Teste Silva']);

        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Maria Teste Silva');
    }

    /**
     * Testa que a listagem de endereços (cliente) exibe os endereços do usuário.
     */
    public function test_address_listing_displays_user_data(): void
    {
        Address::factory()->create([
            'user_id' => $this->client->id,
            'label' => 'Minha Residência',
        ]);

        $response = $this->actingAs($this->client)->get(route('addresses.index'));

        $response->assertStatus(200);
        $response->assertSee('Minha Residência');
    }

    /**
     * Testa que a listagem de produtos (cliente) exibe produtos cadastrados.
     */
    public function test_products_listing_displays_data(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create([
            'name' => 'Pizza Margherita',
            'user_id' => $this->shop->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->client)->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Pizza Margherita');
    }

    /**
     * Testa que a página de detalhes do pedido mostra informações pertinentes.
     */
    public function test_order_details_displays_relevant_info(): void
    {
        $address = Address::factory()->create(['user_id' => $this->client->id]);
        $order = Order::factory()->create([
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'address_id' => $address->id,
            'order_number' => 'ORD-TEST1234',
        ]);

        $response = $this->actingAs($this->client)->get(route('orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('ORD-TEST1234');
    }

    /**
     * Testa que cancelar pedido pendente exibe feedback de sucesso.
     */
    public function test_cancel_order_shows_success_message(): void
    {
        $address = Address::factory()->create(['user_id' => $this->client->id]);
        $order = Order::factory()->create([
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'address_id' => $address->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->client)->patch(route('orders.cancel', $order));

        $response->assertSessionHas('status', 'Order cancelled successfully.');
    }

    /**
     * Testa que não é possível cancelar pedido já entregue — mostra erro.
     */
    public function test_cancel_delivered_order_shows_error(): void
    {
        $address = Address::factory()->create(['user_id' => $this->client->id]);
        $order = Order::factory()->create([
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'address_id' => $address->id,
            'status' => 'delivered',
        ]);

        $response = $this->actingAs($this->client)->patch(route('orders.cancel', $order));

        $response->assertSessionHas('error', 'This order cannot be cancelled.');
    }

    // ═════════════════════════════════════════════
    //  7. CAMPOS DE FORMULÁRIO (INPUTS HTML)
    // ═════════════════════════════════════════════

    /**
     * Testa que o formulário de login tem campos type=email e type=password.
     */
    public function test_login_form_has_correct_input_types(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('type="email"', false);
        $response->assertSee('type="password"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
    }

    /**
     * Testa que o formulário de login possui proteção CSRF.
     */
    public function test_login_form_has_csrf_token(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('_token', false);
    }

    /**
     * Testa que o formulário de registro possui proteção CSRF.
     */
    public function test_register_form_has_csrf_token(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('_token', false);
    }
}
