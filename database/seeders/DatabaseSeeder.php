<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * 
     * Cria dados completos para avaliação do sistema:
     * - 1 Admin
     * - 1 Cliente com endereços
     * - 2 Lojas com produtos
     * - Categorias variadas
     * - Pedidos em diferentes status
     */
    public function run(): void
    {
        // ──────────────────────────────────────
        // 1. USUÁRIOS
        // ──────────────────────────────────────

        $admin = User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@delivery-app.com',
            'password' => bcrypt('password'),
            'type' => 'admin',
            'is_admin' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $client = User::factory()->create([
            'name' => 'Cliente Teste',
            'email' => 'cliente@delivery-app.com',
            'password' => bcrypt('password'),
            'type' => 'client',
            'is_admin' => false,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $shop1 = User::factory()->create([
            'name' => 'Pizzaria do Mario',
            'email' => 'pizzaria@delivery-app.com',
            'password' => bcrypt('password'),
            'type' => 'shop',
            'is_admin' => false,
            'is_active' => true,
            'is_open' => true,
            'email_verified_at' => now(),
        ]);

        $shop2 = User::factory()->create([
            'name' => 'Hamburgueria Top',
            'email' => 'hamburgueria@delivery-app.com',
            'password' => bcrypt('password'),
            'type' => 'shop',
            'is_admin' => false,
            'is_active' => true,
            'is_open' => true,
            'email_verified_at' => now(),
        ]);

        // Usuário inativo para teste
        User::factory()->inactive()->create([
            'name' => 'Usuário Inativo',
            'email' => 'inativo@delivery-app.com',
            'password' => bcrypt('password'),
            'type' => 'client',
            'email_verified_at' => now(),
        ]);

        // ──────────────────────────────────────
        // 2. ENDEREÇOS
        // ──────────────────────────────────────

        $address1 = Address::create([
            'user_id' => $client->id,
            'label' => 'Casa',
            'street' => 'Rua das Flores',
            'number' => '123',
            'complement' => 'Apto 45',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
            'is_default' => true,
        ]);

        Address::create([
            'user_id' => $client->id,
            'label' => 'Trabalho',
            'street' => 'Av. Paulista',
            'number' => '1000',
            'complement' => '5º andar',
            'neighborhood' => 'Bela Vista',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-200',
            'is_default' => false,
        ]);

        // ──────────────────────────────────────
        // 3. CATEGORIAS
        // ──────────────────────────────────────

        $catPizzas = Category::create([
            'name' => 'Pizzas',
            'slug' => 'pizzas',
            'description' => 'Pizzas artesanais assadas no forno a lenha',
            'is_active' => true,
            'order' => 1,
        ]);

        $catHamburgueres = Category::create([
            'name' => 'Hambúrgueres',
            'slug' => 'hamburgueres',
            'description' => 'Hambúrgueres artesanais com carne premium',
            'is_active' => true,
            'order' => 2,
        ]);

        $catBebidas = Category::create([
            'name' => 'Bebidas',
            'slug' => 'bebidas',
            'description' => 'Refrigerantes, sucos e cervejas',
            'is_active' => true,
            'order' => 3,
        ]);

        $catSobremesas = Category::create([
            'name' => 'Sobremesas',
            'slug' => 'sobremesas',
            'description' => 'Doces e sobremesas variadas',
            'is_active' => true,
            'order' => 4,
        ]);

        // Categoria inativa para teste
        Category::create([
            'name' => 'Promoções Antigas',
            'slug' => 'promocoes-antigas',
            'is_active' => false,
            'order' => 99,
        ]);

        // ──────────────────────────────────────
        // 4. PRODUTOS - PIZZARIA
        // ──────────────────────────────────────

        $p1 = Product::create([
            'user_id' => $shop1->id,
            'category_id' => $catPizzas->id,
            'name' => 'Pizza Margherita',
            'slug' => 'pizza-margherita',
            'description' => 'Molho de tomate, mussarela, manjericão fresco',
            'price' => 39.90,
            'promotional_price' => null,
            'stock' => 20,
            'is_active' => true,
            'is_featured' => true,
        ]);

        $p2 = Product::create([
            'user_id' => $shop1->id,
            'category_id' => $catPizzas->id,
            'name' => 'Pizza Calabresa',
            'slug' => 'pizza-calabresa',
            'description' => 'Calabresa fatiada, cebola, azeitona',
            'price' => 42.90,
            'promotional_price' => 35.90,
            'stock' => 15,
            'is_active' => true,
            'is_featured' => true,
        ]);

        Product::create([
            'user_id' => $shop1->id,
            'category_id' => $catBebidas->id,
            'name' => 'Refrigerante 2L',
            'slug' => 'refrigerante-2l',
            'description' => 'Coca-Cola, Guaraná ou Fanta',
            'price' => 12.00,
            'stock' => 50,
            'is_active' => true,
        ]);

        // Produto inativo para teste
        Product::create([
            'user_id' => $shop1->id,
            'category_id' => $catPizzas->id,
            'name' => 'Pizza Especial Descontinuada',
            'slug' => 'pizza-especial-descontinuada',
            'price' => 55.00,
            'stock' => 0,
            'is_active' => false,
        ]);

        // ──────────────────────────────────────
        // 5. PRODUTOS - HAMBURGUERIA
        // ──────────────────────────────────────

        $p3 = Product::create([
            'user_id' => $shop2->id,
            'category_id' => $catHamburgueres->id,
            'name' => 'Smash Burger Clássico',
            'slug' => 'smash-burger-classico',
            'description' => 'Pão brioche, carne 150g, queijo cheddar, cebola caramelizada',
            'price' => 28.90,
            'stock' => 30,
            'is_active' => true,
            'is_featured' => true,
        ]);

        Product::create([
            'user_id' => $shop2->id,
            'category_id' => $catHamburgueres->id,
            'name' => 'Burger Bacon BBQ',
            'slug' => 'burger-bacon-bbq',
            'description' => 'Pão australiano, carne 200g, bacon crocante, molho BBQ',
            'price' => 35.90,
            'promotional_price' => 29.90,
            'stock' => 25,
            'is_active' => true,
        ]);

        Product::create([
            'user_id' => $shop2->id,
            'category_id' => $catBebidas->id,
            'name' => 'Milkshake',
            'slug' => 'milkshake',
            'description' => 'Chocolate, morango ou baunilha - 400ml',
            'price' => 18.00,
            'stock' => 40,
            'is_active' => true,
        ]);

        Product::create([
            'user_id' => $shop2->id,
            'category_id' => $catSobremesas->id,
            'name' => 'Brownie com Sorvete',
            'slug' => 'brownie-com-sorvete',
            'description' => 'Brownie quente com bola de sorvete de creme',
            'price' => 22.00,
            'stock' => 15,
            'is_active' => true,
        ]);

        // ──────────────────────────────────────
        // 6. PEDIDOS EM DIFERENTES STATUS
        // ──────────────────────────────────────

        // Pedido 1: Entregue (pizzaria)
        $order1 = Order::create([
            'user_id' => $client->id,
            'shop_id' => $shop1->id,
            'address_id' => $address1->id,
            'order_number' => 'ORD-SEED0001',
            'status' => 'delivered',
            'payment_method' => 'pix',
            'payment_status' => 'paid',
            'subtotal' => 82.80,
            'delivery_fee' => 5.00,
            'discount' => 0,
            'total' => 87.80,
            'confirmed_at' => now()->subDays(3),
            'delivered_at' => now()->subDays(3)->addHour(),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p1->id,
            'product_name' => 'Pizza Margherita',
            'price' => 39.90,
            'quantity' => 1,
            'subtotal' => 39.90,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p2->id,
            'product_name' => 'Pizza Calabresa',
            'price' => 42.90,
            'quantity' => 1,
            'subtotal' => 42.90,
        ]);

        // Pedido 2: Pendente (hamburgueria)
        $order2 = Order::create([
            'user_id' => $client->id,
            'shop_id' => $shop2->id,
            'address_id' => $address1->id,
            'order_number' => 'ORD-SEED0002',
            'status' => 'pending',
            'payment_method' => 'credit_card',
            'payment_status' => 'pending',
            'subtotal' => 28.90,
            'delivery_fee' => 5.00,
            'discount' => 0,
            'total' => 33.90,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $p3->id,
            'product_name' => 'Smash Burger Clássico',
            'price' => 28.90,
            'quantity' => 1,
            'subtotal' => 28.90,
        ]);

        // Pedido 3: Cancelado
        $order3 = Order::create([
            'user_id' => $client->id,
            'shop_id' => $shop1->id,
            'address_id' => $address1->id,
            'order_number' => 'ORD-SEED0003',
            'status' => 'cancelled',
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'subtotal' => 39.90,
            'delivery_fee' => 5.00,
            'discount' => 0,
            'total' => 44.90,
            'cancelled_at' => now()->subDay(),
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $p1->id,
            'product_name' => 'Pizza Margherita',
            'price' => 39.90,
            'quantity' => 1,
            'subtotal' => 39.90,
        ]);

        // ──────────────────────────────────────
        // RESUMO
        // ──────────────────────────────────────
        echo "\n";
        echo "=== SEED CONCLUÍDO COM SUCESSO ===\n";
        echo "\n";
        echo "CREDENCIAIS DE ACESSO:\n";
        echo "─────────────────────────────────\n";
        echo "Admin:    admin@delivery-app.com / password\n";
        echo "Cliente:  cliente@delivery-app.com / password\n";
        echo "Loja 1:   pizzaria@delivery-app.com / password\n";
        echo "Loja 2:   hamburgueria@delivery-app.com / password\n";
        echo "Inativo:  inativo@delivery-app.com / password\n";
        echo "─────────────────────────────────\n";
        echo "\n";
    }
}
