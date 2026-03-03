<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa que visitante é redirecionado para login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Testa que admin é redirecionado para dashboard admin.
     */
    public function test_admin_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * Testa que shop é redirecionado para dashboard loja.
     */
    public function test_shop_redirected_to_loja_dashboard(): void
    {
        $shop = User::factory()->shop()->create();

        $response = $this->actingAs($shop)->get('/dashboard');

        $response->assertRedirect(route('loja.dashboard'));
    }

    /**
     * Testa que client é redirecionado para home.
     */
    public function test_client_redirected_to_client_home(): void
    {
        $client = User::factory()->client()->create();

        $response = $this->actingAs($client)->get('/dashboard');

        $response->assertRedirect(route('client.home'));
    }

    /**
     * Testa que client NÃO pode acessar admin routes.
     */
    public function test_client_cannot_access_admin_routes(): void
    {
        $client = User::factory()->client()->create();

        $response = $this->actingAs($client)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Testa que client NÃO pode acessar loja routes.
     */
    public function test_client_cannot_access_loja_routes(): void
    {
        $client = User::factory()->client()->create();

        $response = $this->actingAs($client)->get('/loja/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Testa que shop NÃO pode acessar admin routes.
     */
    public function test_shop_cannot_access_admin_routes(): void
    {
        $shop = User::factory()->shop()->create();

        $response = $this->actingAs($shop)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Testa que shop NÃO pode acessar client routes.
     */
    public function test_shop_cannot_access_client_routes(): void
    {
        $shop = User::factory()->shop()->create();

        $response = $this->actingAs($shop)->get('/client/home');

        $response->assertStatus(403);
    }

    /**
     * Testa que admin pode acessar admin dashboard.
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Testa que shop pode acessar loja dashboard.
     */
    public function test_shop_can_access_loja_dashboard(): void
    {
        $shop = User::factory()->shop()->create();

        $response = $this->actingAs($shop)->get('/loja/dashboard');

        $response->assertStatus(200);
    }
}
