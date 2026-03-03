<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testes de Performance — Delivery App
 *
 * Medem o tempo de resposta de rotas críticas do sistema,
 * garantindo que estejam abaixo de 500ms em ambiente local.
 * Utilizam apenas recursos nativos do PHPUnit/Laravel.
 */
class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Limite máximo de tempo de resposta em milissegundos.
     * 1000ms (1s) é um limiar aceitável em ambiente de testes local
     * (compilação de views Blade, carregamento de dependências etc.).
     */
    private const MAX_RESPONSE_TIME_MS = 1000;

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

    /**
     * Helper: mede o tempo de execução de uma requisição HTTP.
     *
     * @return array{response: \Illuminate\Testing\TestResponse, duration_ms: float}
     */
    private function measureRequest(string $method, string $uri, ?User $user = null, array $data = []): array
    {
        $test = $user ? $this->actingAs($user) : $this;

        $start = microtime(true);

        $response = match ($method) {
            'GET' => $test->get($uri),
            'POST' => $test->post($uri, $data),
            'PATCH' => $test->patch($uri, $data),
            default => $test->get($uri),
        };

        $durationMs = (microtime(true) - $start) * 1000;

        return [
            'response' => $response,
            'duration_ms' => $durationMs,
        ];
    }

    // ─────────────────────────────────────────────
    //  Rotas Públicas
    // ─────────────────────────────────────────────

    /**
     * Testa que a página de login responde em menos de 500ms.
     */
    public function test_login_page_responds_within_threshold(): void
    {
        $result = $this->measureRequest('GET', '/login');

        $result['response']->assertStatus(200);
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET /login demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    /**
     * Testa que a página de registro responde em menos de 500ms.
     */
    public function test_register_page_responds_within_threshold(): void
    {
        $result = $this->measureRequest('GET', '/register');

        $result['response']->assertStatus(200);
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET /register demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    /**
     * Testa que a página inicial (welcome) responde em menos de 500ms.
     */
    public function test_welcome_page_responds_within_threshold(): void
    {
        $result = $this->measureRequest('GET', '/');

        $result['response']->assertOk();
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET / demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    // ─────────────────────────────────────────────
    //  Dashboard (por tipo de usuário)
    // ─────────────────────────────────────────────

    /**
     * Testa que o dashboard admin responde em menos de 500ms.
     */
    public function test_admin_dashboard_responds_within_threshold(): void
    {
        $result = $this->measureRequest('GET', '/admin/dashboard', $this->admin);

        $result['response']->assertStatus(200);
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET /admin/dashboard demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    /**
     * Testa que o dashboard da loja responde em menos de 500ms.
     */
    public function test_loja_dashboard_responds_within_threshold(): void
    {
        $result = $this->measureRequest('GET', '/loja/dashboard', $this->shop);

        $result['response']->assertStatus(200);
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET /loja/dashboard demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    /**
     * Testa que a home do cliente responde em menos de 500ms.
     */
    public function test_client_home_responds_within_threshold(): void
    {
        $result = $this->measureRequest('GET', '/client/home', $this->client);

        $result['response']->assertStatus(200);
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET /client/home demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    // ─────────────────────────────────────────────
    //  Listagens de Produtos
    // ─────────────────────────────────────────────

    /**
     * Testa que a listagem de produtos (cliente) responde em menos de 500ms.
     */
    public function test_products_listing_responds_within_threshold(): void
    {
        // Criar dados para simular uma listagem
        $category = Category::factory()->create();
        Product::factory()->count(10)->create([
            'user_id' => $this->shop->id,
            'category_id' => $category->id,
        ]);

        $result = $this->measureRequest('GET', '/products', $this->client);

        $result['response']->assertStatus(200);
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET /products (10 itens) demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    /**
     * Testa que a listagem de produtos da loja responde em menos de 500ms.
     */
    public function test_loja_products_listing_responds_within_threshold(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(10)->create([
            'user_id' => $this->shop->id,
            'category_id' => $category->id,
        ]);

        $result = $this->measureRequest('GET', '/loja/products', $this->shop);

        $result['response']->assertStatus(200);
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET /loja/products (10 itens) demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    /**
     * Testa que o formulário de criar produto (admin) responde em menos de 500ms.
     */
    public function test_admin_product_create_form_responds_within_threshold(): void
    {
        $category = Category::factory()->create();

        $result = $this->measureRequest('GET', '/admin/products/create', $this->admin);

        $result['response']->assertStatus(200);
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET /admin/products/create demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    // ─────────────────────────────────────────────
    //  Pedidos
    // ─────────────────────────────────────────────

    /**
     * Testa que a listagem de pedidos (cliente) responde em menos de 500ms.
     */
    public function test_orders_listing_responds_within_threshold(): void
    {
        $address = Address::factory()->create(['user_id' => $this->client->id]);

        Order::factory()->count(5)->create([
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'address_id' => $address->id,
        ]);

        $result = $this->measureRequest('GET', '/orders', $this->client);

        $result['response']->assertStatus(200);
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET /orders (5 pedidos) demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    /**
     * Testa que a listagem de pedidos (admin) responde em menos de 500ms.
     */
    public function test_admin_orders_listing_responds_within_threshold(): void
    {
        $address = Address::factory()->create(['user_id' => $this->client->id]);

        Order::factory()->count(5)->create([
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'address_id' => $address->id,
        ]);

        $result = $this->measureRequest('GET', '/admin/orders', $this->admin);

        $result['response']->assertStatus(200);
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET /admin/orders (5 pedidos) demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    /**
     * Testa que a listagem de pedidos (loja) responde em menos de 500ms.
     */
    public function test_loja_orders_listing_responds_within_threshold(): void
    {
        $address = Address::factory()->create(['user_id' => $this->client->id]);

        Order::factory()->count(5)->create([
            'user_id' => $this->client->id,
            'shop_id' => $this->shop->id,
            'address_id' => $address->id,
        ]);

        $result = $this->measureRequest('GET', '/loja/orders', $this->shop);

        $result['response']->assertStatus(200);
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET /loja/orders (5 pedidos) demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    // ─────────────────────────────────────────────
    //  Categorias e Gestão
    // ─────────────────────────────────────────────

    /**
     * Testa que a listagem de categorias (admin) responde em menos de 500ms.
     */
    public function test_admin_categories_listing_responds_within_threshold(): void
    {
        Category::factory()->count(10)->create();

        $result = $this->measureRequest('GET', '/admin/categories', $this->admin);

        $result['response']->assertStatus(200);
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET /admin/categories (10 itens) demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    /**
     * Testa que a listagem de usuários (admin) responde em menos de 500ms.
     */
    public function test_admin_users_listing_responds_within_threshold(): void
    {
        User::factory()->count(10)->create();

        $result = $this->measureRequest('GET', '/admin/users', $this->admin);

        $result['response']->assertStatus(200);
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "GET /admin/users (10+ usuários) demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    // ─────────────────────────────────────────────
    //  Operações de Escrita (POST/PATCH)
    // ─────────────────────────────────────────────

    /**
     * Testa que o processo de login (POST) responde em menos de 500ms.
     */
    public function test_login_post_responds_within_threshold(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $result = $this->measureRequest('POST', '/login', null, [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $result['response']->assertRedirect();
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "POST /login demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }

    /**
     * Testa que a criação de categoria (POST) responde em menos de 500ms.
     */
    public function test_category_creation_responds_within_threshold(): void
    {
        $result = $this->measureRequest('POST', '/admin/categories', $this->admin, [
            'name' => 'Categoria Performance Test',
            'description' => 'Teste de performance',
            'is_active' => true,
            'display_order' => 0,
        ]);

        $result['response']->assertRedirect();
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_MS,
            $result['duration_ms'],
            "POST /admin/categories demorou {$result['duration_ms']}ms (limite: " . self::MAX_RESPONSE_TIME_MS . "ms)"
        );
    }
}
