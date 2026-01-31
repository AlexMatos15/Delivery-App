# Guia de Autorização - Policies do Laravel

Este documento descreve o sistema de autorização baseado em Policies implementado na aplicação.

## Visão Geral

As Policies definem as regras de autorização para cada modelo. Elas são verificadas automaticamente nos controllers usando o método `authorize()` ou nas views usando a diretiva `@can`.

## Policies Implementadas

### 1. OrderPolicy

Controla o acesso aos pedidos.

#### Métodos

| Método | Descrição | Permissões |
|--------|-----------|-----------|
| `viewAny()` | Ver lista de pedidos | Lojas e Admins |
| `view()` | Ver detalhes de um pedido | Admin, Loja (seus pedidos), Cliente (seus pedidos) |
| `create()` | Criar novo pedido | Clientes |
| `update()` | Atualizar status do pedido | Admin, Loja (seus pedidos), Cliente (seus pedidos) |
| `cancel()` | Cancelar um pedido | Admin, Cliente (apenas pending/confirmed) |
| `delete()` | Deletar um pedido | Admin |

#### Exemplos de Uso

**No Controller:**
```php
public function show(Order $order)
{
    $this->authorize('view', $order);
    // ...
}
```

**Na View:**
```blade
@can('view', $order)
    <a href="{{ route('loja.orders.show', $order) }}">Ver Detalhes</a>
@endcan

@canany(['update', 'cancel'], $order)
    <button>Gerenciar Pedido</button>
@endcanany
```

### 2. ProductPolicy

Controla o acesso aos produtos.

#### Métodos

| Método | Descrição | Permissões |
|--------|-----------|-----------|
| `viewAny()` | Ver lista de produtos | Todos autenticados |
| `view()` | Ver detalhes de um produto | Todos |
| `create()` | Criar novo produto | Lojas e Admins |
| `update()` | Atualizar um produto | Admin, Loja (seus produtos) |
| `delete()` | Deletar um produto | Admin, Loja (seus produtos) |
| `toggle()` | Ativar/desativar um produto | Admin, Loja (seus produtos) |

#### Exemplos de Uso

**No Controller:**
```php
public function edit(Product $product)
{
    $this->authorize('update', $product);
    // ...
}
```

**Na View:**
```blade
@can('update', $product)
    <a href="{{ route('loja.products.edit', $product) }}">Editar</a>
@endcan

@can('delete', $product)
    <form action="{{ route('loja.products.destroy', $product) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">Deletar</button>
    </form>
@endcan
```

## Componente CanAuthorize

Um componente reusável para verificar autorização nas views.

### Uso

```blade
<x-can-authorize action="update" :model="$product">
    <a href="{{ route('loja.products.edit', $product) }}">Editar Produto</a>
</x-can-authorize>
```

## Gates (Portas de Autorização)

Além das Policies, existem Gates globais para verificações simples.

### Gate para Admin

```blade
@can('admin')
    <a href="{{ route('admin.dashboard') }}">Painel do Admin</a>
@endcan
```

Ou no Controller:

```php
if (auth()->user()->cannot('admin')) {
    abort(403);
}
```

## Usando Authorize em Controllers

### Autorizar contra um modelo

```php
public function show(Order $order)
{
    $this->authorize('view', $order);
    // ...
}
```

### Autorizar contra uma classe

```php
public function create()
{
    $this->authorize('create', Product::class);
    // ...
}
```

### Verificação manual

```php
if (auth()->user()->cannot('update', $product)) {
    abort(403, 'Você não tem permissão para atualizar este produto.');
}
```

## Boas Práticas

1. **Use Policies para Lógica Complexa**: Não espalhe `if` statements pela aplicação. Centralize a lógica de autorização.

2. **Autorize Cedo**: Verifique permissões no início do controller, antes de fazer qualquer processamento.

3. **Use @can nas Views**: Mostre/esconda elementos da UI baseado em permissões.

4. **Combine com Middleware**: Use middleware para proteger rotas inteiras.

5. **Testes**: Sempre teste autorização nos seus testes de feature.

## Teste de Autorização

### Exemplo de Teste

```php
public function test_shop_cannot_view_other_shop_products()
{
    $shop1 = User::factory()->shop()->create();
    $shop2 = User::factory()->shop()->create();
    
    $product = Product::factory()->for($shop1)->create();
    
    $this->actingAs($shop2)->get(route('loja.products.edit', $product))
        ->assertForbidden();
}
```

## Fluxo de Autorização

```
HTTP Request
    ↓
Route Middleware (is_loja, is_admin)
    ↓
Controller (authorize() ou can())
    ↓
Policy (view, update, delete, etc)
    ↓
View (renderiza ou esconde elementos)
```

## Tratamento de Erros

Quando a autorização falha, o Laravel retorna HTTP 403 (Forbidden). Customize a resposta em `resources/views/errors/403.blade.php`.

## Referências

- [Laravel Policies Documentation](https://laravel.com/docs/authorization#creating-policies)
- [Laravel Authorization](https://laravel.com/docs/authorization)
- [Laravel Gates](https://laravel.com/docs/authorization#gates)
