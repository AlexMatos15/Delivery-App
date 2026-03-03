# Casos de Teste — Delivery App (Multi-Loja)

## Convenções

- **Pré-condição padrão**: banco de dados limpo (RefreshDatabase), factories do Laravel disponíveis.
- **Tipo**: U = Unitário, I = Integração (Feature).
- **Prioridade**: Alta / Média / Baixa.

---

## 1. Testes Unitários — Modelo User

| # | Caso de Teste | Tipo | Prioridade | Entrada | Resultado Esperado | Arquivo |
|---|---|---|---|---|---|---|
| U01 | Admin por tipo | U | Alta | User com `type=admin` | `isAdmin()` retorna `true` | `tests/Unit/UserTest.php` |
| U02 | Admin por flag | U | Alta | User com `is_admin=true` | `isAdmin()` retorna `true` | `tests/Unit/UserTest.php` |
| U03 | Não-admin | U | Alta | User com `type=client` | `isAdmin()` retorna `false` | `tests/Unit/UserTest.php` |
| U04 | Tipo loja | U | Alta | User com `type=shop` | `isShop()` retorna `true` | `tests/Unit/UserTest.php` |
| U05 | Não-loja | U | Média | User com `type=client` | `isShop()` retorna `false` | `tests/Unit/UserTest.php` |
| U06 | Tipo cliente | U | Alta | User com `type=client` | `isClient()` retorna `true` | `tests/Unit/UserTest.php` |
| U07 | Não-cliente | U | Média | User com `type=admin` | `isClient()` retorna `false` | `tests/Unit/UserTest.php` |
| U08 | Ativo | U | Alta | User com `is_active=true` | `isActive()` retorna `true` | `tests/Unit/UserTest.php` |
| U09 | Inativo | U | Alta | User com `is_active=false` | `isActive()` retorna `false` | `tests/Unit/UserTest.php` |
| U10 | Produtos da loja | U | Média | User loja com 3 produtos | `products` retorna 3 itens | `tests/Unit/UserTest.php` |
| U11 | Endereços do usuário | U | Média | User com 2 endereços | `addresses` retorna 2 itens | `tests/Unit/UserTest.php` |
| U12 | Pedidos do cliente | U | Média | User com 2 pedidos | `orders` retorna 2 itens | `tests/Unit/UserTest.php` |
| U13 | Pedidos da loja | U | Média | User loja com 2 pedidos | `shopOrders` retorna 2 itens | `tests/Unit/UserTest.php` |

---

## 2. Testes Unitários — Modelo Product

| # | Caso de Teste | Tipo | Prioridade | Entrada | Resultado Esperado | Arquivo |
|---|---|---|---|---|---|---|
| U14 | Preço regular | U | Alta | Produto sem preço promocional | `getCurrentPrice()` retorna preço base | `tests/Unit/ProductTest.php` |
| U15 | Preço promocional | U | Alta | Produto com `promotional_price=15` | `getCurrentPrice()` retorna 15 | `tests/Unit/ProductTest.php` |
| U16 | Em promoção (true) | U | Média | Produto com preço promocional | `isOnSale()` retorna `true` | `tests/Unit/ProductTest.php` |
| U17 | Em promoção (false) | U | Média | Produto sem preço promocional | `isOnSale()` retorna `false` | `tests/Unit/ProductTest.php` |
| U18 | Em estoque (true) | U | Alta | Produto com `stock=10` | `inStock()` retorna `true` | `tests/Unit/ProductTest.php` |
| U19 | Em estoque (false) | U | Alta | Produto com `stock=0` | `inStock()` retorna `false` | `tests/Unit/ProductTest.php` |
| U20 | Slug automático | U | Média | Criar produto "Pizza Grande" | `slug` = `pizza-grande` | `tests/Unit/ProductTest.php` |
| U21 | Slug duplicado | U | Média | 2 produtos com mesmo nome | Segundo slug recebe sufixo | `tests/Unit/ProductTest.php` |
| U22 | Pertence à loja | U | Média | Produto com `user_id` | `user` retorna instância de User | `tests/Unit/ProductTest.php` |
| U23 | Pertence à categoria | U | Média | Produto com `category_id` | `category` retorna instância de Category | `tests/Unit/ProductTest.php` |

---

## 3. Testes Unitários — Modelo Order

| # | Caso de Teste | Tipo | Prioridade | Entrada | Resultado Esperado | Arquivo |
|---|---|---|---|---|---|---|
| U24 | Status pendente | U | Alta | Order novo | `status` = `pending` | `tests/Unit/OrderTest.php` |
| U25 | isPending | U | Alta | Order status=pending | `isPending()` retorna `true` | `tests/Unit/OrderTest.php` |
| U26 | isConfirmed (ativos) | U | Média | Order status=confirmed | Método retorna `true` para confirmed/preparing/out_for_delivery | `tests/Unit/OrderTest.php` |
| U27 | isCompleted | U | Média | Order status=delivered | `isCompleted()` retorna `true` | `tests/Unit/OrderTest.php` |
| U28 | isCancelled | U | Média | Order status=cancelled | `isCancelled()` retorna `true` | `tests/Unit/OrderTest.php` |
| U29 | Formato do número | U | Alta | `generateOrderNumber()` | Formato `ORD-XXXXXXXX` (8 dígitos) | `tests/Unit/OrderTest.php` |
| U30 | Cálculo do total | U | Alta | Order com 2 itens | `calculateTotal()` retorna soma correta | `tests/Unit/OrderTest.php` |
| U31 | Pertence a cliente | U | Média | Order com `user_id` | `customer` retorna User | `tests/Unit/OrderTest.php` |
| U32 | Pertence a loja | U | Média | Order com `shop_id` | `shop` retorna User | `tests/Unit/OrderTest.php` |
| U33 | Tem muitos itens | U | Média | Order com 3 OrderItems | `items` retorna 3 itens | `tests/Unit/OrderTest.php` |
| U34 | Pertence a endereço | U | Média | Order com `address_id` | `address` retorna Address | `tests/Unit/OrderTest.php` |

---

## 4. Testes Unitários — Modelo Category

| # | Caso de Teste | Tipo | Prioridade | Entrada | Resultado Esperado | Arquivo |
|---|---|---|---|---|---|---|
| U35 | Criar categoria | U | Alta | Dados válidos | Categoria persistida no banco | `tests/Unit/CategoryTest.php` |
| U36 | Tem muitos produtos | U | Média | Categoria com 3 produtos | `products` retorna 3 itens | `tests/Unit/CategoryTest.php` |
| U37 | Scope ativos | U | Média | 2 produtos (1 ativo, 1 inativo) | `activeProducts` retorna 1 | `tests/Unit/CategoryTest.php` |
| U38 | Desativar categoria | U | Média | Categoria ativa | `is_active` vira `false` | `tests/Unit/CategoryTest.php` |

---

## 5. Testes Unitários — Modelo Address

| # | Caso de Teste | Tipo | Prioridade | Entrada | Resultado Esperado | Arquivo |
|---|---|---|---|---|---|---|
| U39 | Endereço completo | U | Média | Endereço com complemento | `full_address` inclui complemento | `tests/Unit/AddressTest.php` |
| U40 | Endereço sem complemento | U | Média | Endereço sem complemento | `full_address` não inclui complemento | `tests/Unit/AddressTest.php` |
| U41 | Pertence a usuário | U | Média | Endereço com `user_id` | `user` retorna User | `tests/Unit/AddressTest.php` |
| U42 | Definir como padrão | U | Alta | Endereço com `is_default=false` | Após update, `is_default` = `true` | `tests/Unit/AddressTest.php` |

---

## 6. Testes de Integração — Autorização

| # | Caso de Teste | Tipo | Prioridade | Entrada | Resultado Esperado | Arquivo |
|---|---|---|---|---|---|---|
| I01 | Guest redirecionado | I | Alta | GET `/dashboard` sem auth | Redirect para `/login` | `tests/Feature/AuthorizationTest.php` |
| I02 | Admin → dashboard admin | I | Alta | Admin GET `/dashboard` | Redirect para `/admin/dashboard` | `tests/Feature/AuthorizationTest.php` |
| I03 | Loja → dashboard loja | I | Alta | Loja GET `/dashboard` | Redirect para `/loja/dashboard` | `tests/Feature/AuthorizationTest.php` |
| I04 | Cliente → home cliente | I | Alta | Cliente GET `/dashboard` | Redirect para `/client/home` | `tests/Feature/AuthorizationTest.php` |
| I05 | Cliente ✗ admin | I | Alta | Cliente GET `/admin/dashboard` | Status 403 | `tests/Feature/AuthorizationTest.php` |
| I06 | Cliente ✗ loja | I | Alta | Cliente GET `/loja/dashboard` | Status 403 | `tests/Feature/AuthorizationTest.php` |
| I07 | Loja ✗ admin | I | Alta | Loja GET `/admin/dashboard` | Status 403 | `tests/Feature/AuthorizationTest.php` |
| I08 | Loja ✗ cliente | I | Alta | Loja GET `/client/home` | Status 403 | `tests/Feature/AuthorizationTest.php` |
| I09 | Admin acessa admin | I | Alta | Admin GET `/admin/dashboard` | Status 200 | `tests/Feature/AuthorizationTest.php` |
| I10 | Loja acessa loja | I | Alta | Loja GET `/loja/dashboard` | Status 200 | `tests/Feature/AuthorizationTest.php` |

---

## 7. Testes de Integração — Carrinho de Compras

| # | Caso de Teste | Tipo | Prioridade | Entrada | Resultado Esperado | Arquivo |
|---|---|---|---|---|---|---|
| I11 | Adicionar produto | I | Alta | POST `/cart/add/{id}` com qty=2 | Redirect + sessão contém produto | `tests/Feature/CartTest.php` |
| I12 | Limite de estoque | I | Alta | POST com qty > estoque | Redirect + mensagem de erro | `tests/Feature/CartTest.php` |
| I13 | Atualizar quantidade | I | Média | PATCH `/cart/update/{id}` qty=3 | Sessão atualizada | `tests/Feature/CartTest.php` |
| I14 | Remover produto | I | Média | DELETE `/cart/remove/{id}` | Produto removido da sessão | `tests/Feature/CartTest.php` |
| I15 | Limpar carrinho | I | Média | DELETE `/cart/clear` | Sessão vazia | `tests/Feature/CartTest.php` |
| I16 | Guest bloqueado | I | Alta | Guest POST `/cart/add/{id}` | Redirect para login | `tests/Feature/CartTest.php` |
| I17 | Loja bloqueada | I | Média | Loja POST `/cart/add/{id}` | Status 403 | `tests/Feature/CartTest.php` |

---

## 8. Testes de Integração — Workflow de Pedidos (Loja)

| # | Caso de Teste | Tipo | Prioridade | Entrada | Resultado Esperado | Arquivo |
|---|---|---|---|---|---|---|
| I18 | Confirmar pendente | I | Alta | PATCH status=confirmed | Status muda para confirmed | `tests/Feature/OrderStatusWorkflowTest.php` |
| I19 | Preparar confirmado | I | Alta | PATCH status=preparing | Status muda para preparing | `tests/Feature/OrderStatusWorkflowTest.php` |
| I20 | Saiu para entrega | I | Alta | PATCH status=out_for_delivery | Status muda para out_for_delivery | `tests/Feature/OrderStatusWorkflowTest.php` |
| I21 | Marcar entregue | I | Alta | PATCH status=delivered | Status muda para delivered | `tests/Feature/OrderStatusWorkflowTest.php` |
| I22 | Visualizar pedido | I | Média | GET `/loja/orders/{id}` | Status 200, dados do pedido | `tests/Feature/OrderStatusWorkflowTest.php` |

---

## 9. Testes de Integração — Categorias (Admin)

| # | Caso de Teste | Tipo | Prioridade | Entrada | Resultado Esperado | Arquivo |
|---|---|---|---|---|---|---|
| I23 | Listar categorias | I | Alta | GET `/admin/categories` | Status 200 | `tests/Feature/AdminCategoryTest.php` |
| I24 | Criar categoria | I | Alta | POST com dados válidos | Redirect + registro no banco | `tests/Feature/AdminCategoryTest.php` |
| I25 | Editar categoria | I | Alta | PUT com dados atualizados | Redirect + dados atualizados | `tests/Feature/AdminCategoryTest.php` |
| I26 | Toggle status | I | Média | PATCH toggle | is_active invertido | `tests/Feature/AdminCategoryTest.php` |
| I27 | Excluir categoria | I | Média | DELETE | Registro removido | `tests/Feature/AdminCategoryTest.php` |
| I28 | Validação: nome obrigatório | I | Alta | POST sem nome | Erro de validação | `tests/Feature/AdminCategoryTest.php` |

---

## 10. Testes de Integração — Produtos (Loja)

| # | Caso de Teste | Tipo | Prioridade | Entrada | Resultado Esperado | Arquivo |
|---|---|---|---|---|---|---|
| I29 | Listar produtos | I | Alta | GET `/loja/products` | Status 200 | `tests/Feature/ShopProductTest.php` |
| I30 | Criar produto | I | Alta | POST com dados válidos | Redirect + registro no banco | `tests/Feature/ShopProductTest.php` |
| I31 | Editar produto | I | Alta | PUT com dados atualizados | Redirect + dados atualizados | `tests/Feature/ShopProductTest.php` |
| I32 | Toggle status | I | Média | PATCH toggle | is_active invertido | `tests/Feature/ShopProductTest.php` |
| I33 | Validação: preço obrigatório | I | Alta | POST sem preço | Erro de validação | `tests/Feature/ShopProductTest.php` |

---

## 11. Testes de Integração — Endereços (Cliente)

| # | Caso de Teste | Tipo | Prioridade | Entrada | Resultado Esperado | Arquivo |
|---|---|---|---|---|---|---|
| I34 | Criar endereço | I | Alta | POST com dados válidos | Redirect + registro no banco | `tests/Feature/AddressTest.php` |
| I35 | Editar endereço | I | Alta | PUT com dados atualizados | Redirect + dados atualizados | `tests/Feature/AddressTest.php` |
| I36 | Definir como padrão | I | Média | PATCH set-default | Endereço marcado como padrão | `tests/Feature/AddressTest.php` |
| I37 | Excluir endereço | I | Média | DELETE | Registro removido | `tests/Feature/AddressTest.php` |
| I38 | Validação: rua obrigatória | I | Alta | POST sem rua | Erro de validação | `tests/Feature/AddressTest.php` |

---

## Resumo Quantitativo

| Categoria | Quantidade |
|---|---|
| Testes Unitários | 42 |
| Testes de Integração | 38 |
| **Total de Casos** | **80** |
| Prioridade Alta | 52 |
| Prioridade Média | 28 |

> **Nota**: Além dos 80 casos listados acima, o projeto inclui os testes padrão do Laravel Breeze (autenticação, registro, reset de senha, verificação de e-mail, atualização de perfil) totalizando **103 testes automatizados**.
