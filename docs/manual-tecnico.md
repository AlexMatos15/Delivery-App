# Manual Técnico — Delivery App (Multi-Loja)

## 1. Visão Geral da Arquitetura

### 1.1 Stack Tecnológica

| Componente | Tecnologia | Versão |
|---|---|---|
| Linguagem Backend | PHP | ^8.2 |
| Framework Backend | Laravel | 12.x |
| Banco de Dados | MySQL | 8.0+ |
| Banco de Testes | SQLite | in-memory |
| Autenticação | Laravel Breeze | 2.x |
| Template Admin | AdminLTE | 3.x |
| CSS Framework | Tailwind CSS | 4.x |
| Bundler | Vite | 6.x |
| Testes | PHPUnit | 11.x |
| Servidor Local | XAMPP | 8.2+ |

### 1.2 Padrão Arquitetural

O sistema segue o padrão **MVC (Model-View-Controller)** do Laravel:

```
Requisição HTTP
    │
    ▼
routes/web.php  ──→  Middleware (auth, verified, is_admin, is_loja, is_cliente)
    │
    ▼
Controller (app/Http/Controllers/)
    │
    ├──→ Model (app/Models/)  ──→  Database (MySQL)
    │
    ▼
View (resources/views/)  ──→  Resposta HTML
```

### 1.3 Estrutura de Diretórios

```
delivery-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                    # Controllers do painel admin
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── OrderManagementController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   └── UserManagementController.php
│   │   │   ├── Client/                   # Controllers do cliente
│   │   │   │   ├── CheckoutController.php
│   │   │   │   └── ClientHomeController.php
│   │   │   ├── Loja/                     # Controllers da loja
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   └── ReportsController.php
│   │   │   ├── AddressController.php
│   │   │   ├── CartController.php
│   │   │   ├── Controller.php            # Base controller (AuthorizesRequests)
│   │   │   ├── DashboardRedirectController.php
│   │   │   ├── OrderController.php
│   │   │   ├── ProductController.php
│   │   │   └── ProfileController.php
│   │   ├── Middleware/
│   │   │   ├── EnsureAdmin.php           # Alias: is_admin
│   │   │   ├── EnsureUserIsActive.php    # Logout de usuários inativos
│   │   │   ├── EnsureUserIsCliente.php   # Alias: is_cliente
│   │   │   └── EnsureUserIsLoja.php      # Alias: is_loja
│   │   └── Requests/                     # Form Requests
│   ├── Models/
│   │   ├── Address.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Product.php
│   │   └── User.php
│   ├── Policies/
│   │   ├── OrderPolicy.php
│   │   └── ProductPolicy.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── AuthServiceProvider.php
│   └── View/
│       ├── Components/
│       └── Composers/
├── bootstrap/
│   └── app.php                           # Middleware registration
├── config/                               # Configurações do Laravel
├── database/
│   ├── factories/                        # Factories para testes
│   ├── migrations/                       # Migrations do banco
│   └── seeders/                          # Seeders de dados
├── docs/                                 # Documentação do projeto
├── public/                               # Arquivos públicos (entrypoint)
├── resources/
│   ├── css/app.css                       # Tailwind CSS
│   ├── js/app.js                         # JavaScript principal
│   └── views/                            # Templates Blade
│       ├── admin/                        # Views AdminLTE
│       ├── addresses/                    # CRUD de endereços
│       ├── auth/                         # Login, registro, etc.
│       ├── cart/                         # Carrinho de compras
│       ├── client/                       # Views do cliente
│       ├── components/                   # Componentes Blade
│       ├── layouts/                      # Layouts base
│       ├── loja/                         # Views da loja
│       ├── orders/                       # Pedidos do cliente
│       ├── products/                     # Catálogo público
│       └── profile/                      # Perfil do usuário
├── routes/
│   ├── auth.php                          # Rotas Breeze (login, registro)
│   └── web.php                           # Rotas principais
├── storage/                              # Uploads, logs, cache
├── tests/
│   ├── Unit/                             # Testes unitários
│   └── Feature/                          # Testes de integração
├── composer.json                         # Dependências PHP
├── package.json                          # Dependências Node.js
├── phpunit.xml                           # Configuração de testes
├── tailwind.config.js                    # Configuração Tailwind
└── vite.config.js                        # Configuração Vite
```

---

## 2. Modelo de Dados

### 2.1 Diagrama Entidade-Relacionamento (Textual)

```
┌─────────────────┐       ┌─────────────────┐
│     users        │       │   categories     │
│─────────────────│       │─────────────────│
│ id (PK)          │       │ id (PK)          │
│ name             │       │ name             │
│ email            │       │ slug             │
│ type (enum)      │       │ description      │
│ is_admin         │       │ image            │
│ is_active        │       │ is_active        │
│ is_open          │       │ display_order    │
│ password         │       └────────┬────────┘
│ ...              │                │
└───┬──────┬───┬──┘                │ 1:N
    │      │   │                   │
    │      │   │    ┌──────────────┴──────┐
    │      │   └───►│     products         │
    │      │  1:N   │─────────────────────│
    │      │        │ id (PK)              │
    │      │        │ user_id (FK→users)   │
    │      │        │ category_id (FK)     │
    │      │        │ name, slug           │
    │      │        │ price                │
    │      │        │ promotional_price    │
    │      │        │ stock                │
    │      │        │ is_active            │
    │      │        │ is_featured          │
    │      │        └──────────┬──────────┘
    │      │                   │
    │ 1:N  │ 1:N               │
    │      │                   │
    ▼      ▼                   │
┌─────────┐  ┌────────────┐    │
│addresses│  │  orders     │    │
│─────────│  │────────────│    │
│ id (PK) │  │ id (PK)    │    │
│ user_id │  │ user_id    │    │
│ label   │  │ shop_id    │    │
│ street  │  │ address_id │    │
│ number  │  │ order_number│   │
│ ...     │  │ status     │    │
│ is_def. │  │ payment_*  │    │
└─────────┘  │ subtotal   │    │
             │ delivery_fee│   │
             │ total       │    │
             └─────┬──────┘    │
                   │           │
                   │ 1:N       │
                   ▼           │
            ┌──────────────┐   │
            │ order_items   │   │
            │──────────────│   │
            │ id (PK)       │   │
            │ order_id (FK) │   │
            │ product_id(FK)├───┘
            │ product_name  │ (snapshot)
            │ price         │ (snapshot)
            │ quantity      │
            │ subtotal      │
            └──────────────┘
```

### 2.2 Tabelas do Banco de Dados

#### `users`
| Coluna | Tipo | Descrição |
|---|---|---|
| `id` | bigint (PK) | Identificador único |
| `name` | string | Nome completo |
| `email` | string (unique) | E-mail de login |
| `type` | enum(client,admin,shop) | Tipo de usuário |
| `is_admin` | boolean | Flag administrativa |
| `is_active` | boolean | Conta ativa |
| `is_open` | boolean | Loja aberta (para tipo shop) |
| `email_verified_at` | timestamp | Data de verificação |
| `password` | string | Senha criptografada (bcrypt) |
| `remember_token` | string | Token "lembrar-me" |
| `created_at` | timestamp | Data de criação |
| `updated_at` | timestamp | Data de atualização |

#### `categories`
| Coluna | Tipo | Descrição |
|---|---|---|
| `id` | bigint (PK) | Identificador único |
| `name` | string | Nome da categoria |
| `slug` | string (unique) | Slug para URL |
| `description` | text | Descrição opcional |
| `image` | string | Caminho da imagem |
| `is_active` | boolean | Categoria ativa |
| `display_order` | integer | Ordem de exibição |
| `created_at` / `updated_at` | timestamps | Datas de auditoria |

#### `products`
| Coluna | Tipo | Descrição |
|---|---|---|
| `id` | bigint (PK) | Identificador único |
| `user_id` | FK → users | Loja proprietária |
| `category_id` | FK → categories | Categoria do produto |
| `name` | string | Nome do produto |
| `slug` | string (unique) | Slug para URL |
| `description` | text | Descrição opcional |
| `image` | string | Caminho da imagem |
| `price` | decimal(10,2) | Preço regular |
| `promotional_price` | decimal(10,2) | Preço promocional |
| `stock` | integer | Quantidade em estoque |
| `is_active` | boolean | Produto ativo |
| `is_featured` | boolean | Produto em destaque |
| `created_at` / `updated_at` | timestamps | Datas de auditoria |

#### `addresses`
| Coluna | Tipo | Descrição |
|---|---|---|
| `id` | bigint (PK) | Identificador único |
| `user_id` | FK → users | Proprietário |
| `label` | string | Rótulo (Casa, Trabalho) |
| `street` | string | Rua |
| `number` | string | Número |
| `complement` | string | Complemento |
| `neighborhood` | string | Bairro |
| `city` | string | Cidade |
| `state` | string(2) | UF |
| `zip_code` | string(9) | CEP |
| `reference` | text | Ponto de referência |
| `is_default` | boolean | Endereço padrão |
| `created_at` / `updated_at` | timestamps | Datas de auditoria |

#### `orders`
| Coluna | Tipo | Descrição |
|---|---|---|
| `id` | bigint (PK) | Identificador único |
| `user_id` | FK → users | Cliente |
| `shop_id` | FK → users | Loja |
| `address_id` | FK → addresses | Endereço de entrega |
| `order_number` | string (unique) | Número (ORD-XXXXXXXX) |
| `status` | enum | Status do pedido |
| `payment_method` | enum | Método de pagamento |
| `payment_status` | enum | Status do pagamento |
| `subtotal` | decimal(10,2) | Subtotal dos itens |
| `delivery_fee` | decimal(10,2) | Taxa de entrega |
| `discount` | decimal(10,2) | Desconto aplicado |
| `total` | decimal(10,2) | Valor total |
| `notes` | text | Observações |
| `confirmed_at` | timestamp | Data de confirmação |
| `delivered_at` | timestamp | Data de entrega |
| `cancelled_at` | timestamp | Data de cancelamento |
| `created_at` / `updated_at` | timestamps | Datas de auditoria |

**Status possíveis**: `pending` → `confirmed` → `preparing` → `out_for_delivery` → `delivered` | `cancelled`

**Métodos de pagamento**: `cash`, `credit_card`, `debit_card`, `pix`

**Status de pagamento**: `pending`, `paid`, `refunded`

#### `order_items`
| Coluna | Tipo | Descrição |
|---|---|---|
| `id` | bigint (PK) | Identificador único |
| `order_id` | FK → orders | Pedido |
| `product_id` | FK → products | Produto |
| `product_name` | string | Nome do produto (snapshot) |
| `price` | decimal(10,2) | Preço unitário (snapshot) |
| `quantity` | integer | Quantidade |
| `subtotal` | decimal(10,2) | Subtotal da linha |
| `notes` | text | Observações do item |
| `created_at` / `updated_at` | timestamps | Datas de auditoria |

> **Snapshots**: `product_name` e `price` são salvos no momento do pedido para preservar o histórico, mesmo que o produto seja alterado posteriormente.

---

## 3. Autenticação e Autorização

### 3.1 Sistema de Autenticação

Utiliza **Laravel Breeze** com sessão PHP:
- Login/Logout com e-mail e senha
- Registro com verificação de e-mail
- Reset de senha por e-mail
- Proteção CSRF em todos os formulários

### 3.2 Tipos de Usuário

| Tipo | Campo `type` | Acesso |
|---|---|---|
| Cliente | `client` | Catálogo, carrinho, pedidos, endereços |
| Loja | `shop` | Dashboard, produtos, pedidos, relatórios |
| Administrador | `admin` | Tudo: usuários, categorias, produtos, pedidos |

### 3.3 Middleware de Autorização

| Middleware | Alias | Arquivo | Função |
|---|---|---|---|
| `EnsureAdmin` | `is_admin` | `app/Http/Middleware/EnsureAdmin.php` | Bloqueia não-admins (403) |
| `EnsureUserIsLoja` | `is_loja` | `app/Http/Middleware/EnsureUserIsLoja.php` | Bloqueia não-lojas (403) |
| `EnsureUserIsCliente` | `is_cliente` | `app/Http/Middleware/EnsureUserIsCliente.php` | Bloqueia não-clientes (403) |
| `EnsureUserIsActive` | — | `app/Http/Middleware/EnsureUserIsActive.php` | Logout de inativos |

Registro em `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'is_admin' => EnsureAdmin::class,
        'is_loja' => EnsureUserIsLoja::class,
        'is_cliente' => EnsureUserIsCliente::class,
    ]);
    $middleware->append(EnsureUserIsActive::class);
})
```

### 3.4 Policies

| Policy | Modelo | Regras |
|---|---|---|
| `OrderPolicy` | Order | Admin: tudo. Loja: apenas seus pedidos. Cliente: apenas seus pedidos. |
| `ProductPolicy` | Product | Loja: apenas seus produtos. Admin: todos. |

---

## 4. Rotas da Aplicação

### 4.1 Rotas Públicas

| Método | URI | Controller | Nome |
|---|---|---|---|
| GET | `/` | Closure | `welcome` |

### 4.2 Rotas do Cliente (middleware: `auth`, `verified`, `is_cliente`)

| Método | URI | Controller | Nome |
|---|---|---|---|
| GET | `/client/home` | `Client\ClientHomeController@index` | `client.home` |
| POST | `/client/store/select` | `Client\ClientHomeController@selectStore` | `client.store.select` |
| GET | `/products` | `ProductController@index` | `products.index` |
| GET | `/products/{product}` | `ProductController@show` | `products.show` |
| GET | `/cart` | `CartController@index` | `cart.index` |
| POST | `/cart/add/{product}` | `CartController@add` | `cart.add` |
| PATCH | `/cart/update/{product}` | `CartController@update` | `cart.update` |
| DELETE | `/cart/remove/{product}` | `CartController@remove` | `cart.remove` |
| DELETE | `/cart/clear` | `CartController@clear` | `cart.clear` |
| GET | `/cart/count` | `CartController@count` | `cart.count` |
| GET | `/checkout` | `Client\CheckoutController@index` | `client.checkout` |
| POST | `/checkout` | `Client\CheckoutController@store` | `client.store` |
| GET | `/checkout/confirmation/{order}` | `Client\CheckoutController@confirmation` | `client.order-confirmation` |

### 4.3 Rotas Autenticadas (todos os tipos)

| Método | URI | Controller | Nome |
|---|---|---|---|
| GET | `/dashboard` | `DashboardRedirectController` | `dashboard` |
| GET | `/profile` | `ProfileController@edit` | `profile.edit` |
| PATCH | `/profile` | `ProfileController@update` | `profile.update` |
| GET | `/my-profile` | `ProfileController@show` | `profile.show` |
| RESOURCE | `/addresses` | `AddressController` | `addresses.*` |
| PATCH | `/addresses/{address}/set-default` | `AddressController@setDefault` | `addresses.set-default` |
| GET | `/orders` | `OrderController@index` | `orders.index` |
| GET | `/orders/checkout` | `OrderController@checkout` | `orders.checkout` |
| POST | `/orders` | `OrderController@store` | `orders.store` |
| GET | `/orders/{order}` | `OrderController@show` | `orders.show` |
| PATCH | `/orders/{order}/cancel` | `OrderController@cancel` | `orders.cancel` |

### 4.4 Rotas da Loja (middleware: `auth`, `verified`, `is_loja`)

| Método | URI | Controller | Nome |
|---|---|---|---|
| GET | `/loja/dashboard` | `Loja\DashboardController@index` | `loja.dashboard` |
| POST | `/loja/toggle-status` | `Loja\DashboardController@toggleStatus` | `loja.toggleStatus` |
| GET | `/loja/orders` | `Loja\OrderController@index` | `loja.orders.index` |
| GET | `/loja/orders/{order}` | `Loja\OrderController@show` | `loja.orders.show` |
| PATCH | `/loja/orders/{order}/status` | `Loja\OrderController@updateStatus` | `loja.orders.update-status` |
| RESOURCE | `/loja/products` | `Loja\ProductController` | `loja.products.*` |
| PATCH | `/loja/products/{product}/toggle` | `Loja\ProductController@toggleActive` | `loja.products.toggle` |
| POST | `/loja/categories` | `Loja\ProductController@storeCategory` | `loja.categories.store` |
| GET | `/loja/reports/dashboard` | `Loja\ReportsController@dashboard` | `loja.reports.dashboard` |
| GET | `/loja/reports/sales` | `Loja\ReportsController@sales` | `loja.reports.sales` |
| GET | `/loja/reports/customers` | `Loja\ReportsController@customers` | `loja.reports.customers` |

### 4.5 Rotas do Admin (middleware: `auth`, `verified`, `is_admin`)

| Método | URI | Controller | Nome |
|---|---|---|---|
| GET | `/admin/dashboard` | `Admin\DashboardController@index` | `admin.dashboard` |
| GET | `/admin/orders` | `Admin\OrderManagementController@index` | `admin.orders.index` |
| GET | `/admin/orders/{order}` | `Admin\OrderManagementController@show` | `admin.orders.show` |
| PATCH | `/admin/orders/{order}/status` | `Admin\OrderManagementController@updateStatus` | `admin.orders.update-status` |
| PATCH | `/admin/orders/{order}/payment` | `Admin\OrderManagementController@updatePaymentStatus` | `admin.orders.update-payment` |
| GET | `/admin/users` | `Admin\UserManagementController@index` | `admin.users.index` |
| PATCH | `/admin/users/{user}/toggle` | `Admin\UserManagementController@toggleActive` | `admin.users.toggle` |
| DELETE | `/admin/users/{user}` | `Admin\UserManagementController@destroy` | `admin.users.destroy` |
| RESOURCE | `/admin/categories` | `Admin\CategoryController` | `admin.categories.*` |
| PATCH | `/admin/categories/{category}/toggle` | `Admin\CategoryController@toggleActive` | `admin.categories.toggle` |
| RESOURCE | `/admin/products` | `Admin\ProductController` | `admin.products.*` |
| PATCH | `/admin/products/{product}/toggle` | `Admin\ProductController@toggleActive` | `admin.products.toggle` |

---

## 5. Fluxos Principais

### 5.1 Fluxo de Pedido (Cliente)

```
1. Cliente seleciona loja
2. Navega pelo catálogo de produtos
3. Adiciona produtos ao carrinho (validação de estoque)
4. Acessa checkout
5. Seleciona endereço de entrega
6. Escolhe método de pagamento
7. Confirma pedido
   → Pedido criado com status "pending"
   → Estoque decrementado
   → Carrinho limpo
8. Acompanha status em "Meus Pedidos"
```

### 5.2 Fluxo de Pedido (Loja)

```
1. Loja recebe pedido (status: pending)
2. Confirma pedido → status: confirmed
3. Inicia preparação → status: preparing
4. Envia para entrega → status: out_for_delivery
5. Confirma entrega → status: delivered
```

### 5.3 Fluxo de Cancelamento

```
1. Cliente ou loja solicita cancelamento
2. Validação: status deve ser "pending" ou "confirmed"
3. Status alterado para "cancelled"
4. Estoque dos produtos restaurado automaticamente
5. Data de cancelamento registrada (cancelled_at)
```

### 5.4 Redirect de Dashboard

```
DashboardRedirectController (invokable):
  - Se admin  → redirect /admin/dashboard
  - Se loja   → redirect /loja/dashboard
  - Se cliente → redirect /client/home
```

---

## 6. Instalação e Configuração

### 6.1 Pré-requisitos

- PHP >= 8.2 com extensões: PDO, Mbstring, OpenSSL, Tokenizer, XML, Ctype, JSON, BCMath
- Composer >= 2.0
- Node.js >= 18 com NPM
- MySQL >= 8.0 (ou MariaDB 10.4+)
- Git

### 6.2 Instalação

```bash
# 1. Clonar repositório
git clone <url-do-repo> delivery-app
cd delivery-app

# 2. Instalar dependências PHP
composer install

# 3. Instalar dependências Node.js
npm install

# 4. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 5. Configurar banco de dados no .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=delivery_app
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Executar migrations
php artisan migrate

# 7. Executar seeders (dados de demonstração)
php artisan db:seed

# 8. Criar link simbólico do storage
php artisan storage:link

# 9. Compilar assets
npm run build

# 10. Iniciar servidor
php artisan serve
```

### 6.3 Instalação Rápida (Script)

```bash
composer run setup
```

### 6.4 Ambiente de Desenvolvimento

```bash
# Inicia PHP server + Vite + Queue + Logs
composer run dev
```

### 6.5 Configuração de Testes

O arquivo `phpunit.xml` já está configurado:
```xml
<env name="APP_ENV" value="testing"/>
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
<env name="SESSION_DRIVER" value="array"/>
<env name="CACHE_STORE" value="array"/>
```

Executar testes:
```bash
php artisan test
```

---

## 7. Dados de Demonstração (Seeder)

O `DatabaseSeeder` cria dados completos para avaliação:

### 7.1 Usuários

| Tipo | E-mail | Senha |
|---|---|---|
| Admin | `admin@delivery-app.com` | `password` |
| Cliente | `cliente@delivery-app.com` | `password` |
| Loja (Pizzaria) | `pizzaria@delivery-app.com` | `password` |
| Loja (Hamburgueria) | `hamburgueria@delivery-app.com` | `password` |
| Inativo | `inativo@delivery-app.com` | `password` |

### 7.2 Categorias

Pizzas, Hambúrgueres, Bebidas, Sobremesas, Porções — todas ativas com ordem de exibição.

### 7.3 Produtos

7+ produtos distribuídos entre as duas lojas, incluindo:
- Produtos com preço promocional
- Produtos em destaque (featured)
- Produto inativo (Milkshake)
- Variados níveis de estoque

### 7.4 Pedidos

3 pedidos em diferentes status:
- Pedido entregue (com datas completas)
- Pedido pendente
- Pedido cancelado (com estoque restaurado)

### 7.5 Executar Seed

```bash
# Apenas seed (banco já existente)
php artisan db:seed

# Limpar e re-seed
php artisan migrate:fresh --seed
```

---

## 8. Testes Automatizados

### 8.1 Estrutura

```
tests/
├── Unit/
│   ├── AddressTest.php       (4 testes)
│   ├── CategoryTest.php      (4 testes)
│   ├── OrderTest.php         (11 testes)
│   ├── ProductTest.php       (10 testes)
│   └── UserTest.php          (13 testes)
└── Feature/
    ├── AddressTest.php       (5 testes)
    ├── AdminCategoryTest.php (6 testes)
    ├── AuthorizationTest.php (10 testes)
    ├── CartTest.php          (7 testes)
    ├── OrderStatusWorkflowTest.php (5 testes)
    ├── ShopProductTest.php   (5 testes)
    └── Auth/                 (15 testes - Breeze)
```

### 8.2 Factories

| Factory | Estados |
|---|---|
| `UserFactory` | `admin()`, `shop()`, `client()`, `inactive()` |
| `CategoryFactory` | `inactive()` |
| `ProductFactory` | `onSale()`, `outOfStock()`, `inactive()`, `featured()` |
| `AddressFactory` | `default()` |
| `OrderFactory` | `confirmed()`, `preparing()`, `delivered()`, `cancelled()`, `paid()` |
| `OrderItemFactory` | — |

### 8.3 Comandos

```bash
# Todos os testes
php artisan test

# Apenas unitários
php artisan test --testsuite=Unit

# Apenas feature
php artisan test --testsuite=Feature

# Teste específico
php artisan test --filter="admin_can_list_categories"

# Verbose
php artisan test -v
```

---

## 9. Variáveis de Ambiente

| Variável | Descrição | Exemplo |
|---|---|---|
| `APP_NAME` | Nome da aplicação | `DeliveryApp` |
| `APP_ENV` | Ambiente | `local`, `production` |
| `APP_KEY` | Chave de criptografia | Gerada por `artisan key:generate` |
| `APP_DEBUG` | Modo debug | `true` (dev), `false` (prod) |
| `APP_URL` | URL base | `http://localhost:8000` |
| `DB_CONNECTION` | Driver do banco | `mysql` |
| `DB_HOST` | Host do banco | `127.0.0.1` |
| `DB_PORT` | Porta | `3306` |
| `DB_DATABASE` | Nome do banco | `delivery_app` |
| `DB_USERNAME` | Usuário | `root` |
| `DB_PASSWORD` | Senha | — |
| `MAIL_MAILER` | Driver de e-mail | `smtp`, `log` |
| `SESSION_DRIVER` | Driver de sessão | `file`, `database` |
| `CACHE_STORE` | Driver de cache | `file`, `redis` |

---

## 10. Troubleshooting

### Erro 419 (CSRF Token Mismatch)
```bash
php artisan config:clear
php artisan cache:clear
```
Verifique se o formulário inclui `@csrf`.

### Erro 500 ao acessar storage
```bash
php artisan storage:link
```

### Migrations falham
```bash
php artisan migrate:fresh   # CUIDADO: apaga tudo
```

### Assets não carregam
```bash
npm run build
# ou em desenvolvimento:
npm run dev
```

### Testes falham com "table not found"
Verifique se `phpunit.xml` tem `DB_CONNECTION=sqlite` e `DB_DATABASE=:memory:`.

---

## 11. Histórico de Versões

| Versão | Data | Descrição |
|---|---|---|
| 1.0.0 | 2025-06-01 | Versão inicial com todos os módulos implementados |
