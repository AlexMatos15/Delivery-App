# Plano de Testes — Delivery App (Multi-Loja)

## 1. Introdução

### 1.1 Objetivo
Este documento descreve a estratégia, o escopo e os procedimentos de teste para o sistema **Delivery App**, uma aplicação web multi-loja de delivery desenvolvida em Laravel 12. O plano visa garantir a qualidade funcional, a segurança e a confiabilidade do sistema antes da entrega final.

### 1.2 Escopo
O plano cobre testes **unitários**, **de integração (feature)**, **de performance** e **de usabilidade** automatizados, abrangendo:
- Modelos e regras de negócio (User, Product, Order, Category, Address)
- Autenticação e autorização (multi-role: admin, loja, cliente)
- Fluxos funcionais (carrinho, pedidos, checkout, gestão de produtos/categorias)
- Gerenciamento de endereços
- Workflow de status de pedidos
- Tempo de resposta das rotas críticas (performance)
- Labels, botões, mensagens de feedback, validação e redirecionamentos (usabilidade)

### 1.3 Fora do Escopo
- Testes de interface (E2E com Dusk/Cypress)
- Testes de carga com múltiplos usuários simultâneos
- Testes de segurança avançados (pentest)
- Testes de integração com gateways de pagamento (não implementado)

---

## 2. Referências

| Documento | Descrição |
|---|---|
| `docs/casos-de-teste.md` | Casos de teste detalhados |
| `docs/relatorio-de-testes.md` | Relatório de execução dos testes |
| `docs/manual-tecnico.md` | Documentação técnica do sistema |
| `README.md` | Visão geral do projeto |

---

## 3. Estratégia de Testes

### 3.1 Níveis de Teste

| Nível | Ferramenta | Objetivo | Cobertura Alvo |
|---|---|---|---|
| **Unitário** | PHPUnit | Validar modelos, atributos, relacionamentos e métodos | Todos os 6 modelos |
| **Integração (Feature)** | PHPUnit + Laravel HTTP Tests | Validar rotas, middleware, controllers e fluxos completos | Todas as áreas funcionais |
| **Performance** | PHPUnit + `microtime()` | Medir tempo de resposta de rotas críticas (< 1000ms) | Rotas públicas, dashboards, listagens e operações de escrita |
| **Usabilidade** | PHPUnit + Laravel HTTP Tests | Validar labels, feedback, validação e navegação | Formulários, mensagens e redirecionamentos |

### 3.2 Tipos de Teste

- **Testes de Modelo**: Verificam atributos, acessores, mutadores, escopos e relacionamentos Eloquent.
- **Testes de Autorização**: Validam que cada tipo de usuário (admin, loja, cliente) só acessa rotas permitidas.
- **Testes Funcionais**: Simulam interações HTTP completas (CRUD de categorias, produtos, endereços, pedidos).
- **Testes de Workflow**: Verificam a progressão de status dos pedidos (pending → confirmed → preparing → out_for_delivery → delivered).
- **Testes de Validação**: Garantem que regras de validação aplicam-se corretamente (campos obrigatórios, limites).
- **Testes de Performance**: Medem o tempo de resposta de rotas críticas e verificam que estão abaixo do limiar definido (1000ms). Cobrem rotas públicas, dashboards de cada role, listagens de dados e operações de escrita (POST).
- **Testes de Usabilidade**: Verificam a presença de labels, botões e textos corretos nos formulários; validam que mensagens de feedback (sucesso e erro) são exibidas ao usuário; confirmam que erros de validação são retornados adequadamente; testam redirecionamentos após ações CRUD; asseguram que dados cadastrados são visíveis nas listagens.

### 3.3 Ambiente de Testes

| Componente | Configuração |
|---|---|
| **Framework** | Laravel 12 + PHPUnit 11 |
| **Banco de Dados** | SQLite in-memory (`:memory:`) |
| **Isolamento** | Trait `RefreshDatabase` em cada classe de teste |
| **Sessão** | Driver `array` (em memória) |
| **Cache** | Driver `array` (em memória) |
| **Dados de Teste** | Factories do Laravel (Faker) |

Configuração em `phpunit.xml`:
```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
<env name="SESSION_DRIVER" value="array"/>
<env name="CACHE_STORE" value="array"/>
```

---

## 4. Critérios de Entrada e Saída

### 4.1 Critérios de Entrada
- Código-fonte compilável sem erros
- Migrations executando sem falhas no SQLite in-memory
- Factories criando instâncias válidas para todos os modelos
- Ambiente de teste configurado (`phpunit.xml`)

### 4.2 Critérios de Saída (Aprovação)
- **100% dos testes passando** (0 falhas, 0 erros)
- Cobertura de todos os modelos do sistema
- Cobertura de todas as áreas funcionais principais
- Tempo de execução total inferior a 30 segundos

### 4.3 Critérios de Suspensão
- Falha em mais de 20% dos testes → investigar causa raiz
- Erro de infraestrutura (SQLite, migrations) → corrigir antes de prosseguir

---

## 5. Recursos

### 5.1 Ferramentas

| Ferramenta | Versão | Finalidade |
|---|---|---|
| PHP | 8.2.12 | Runtime |
| Laravel | 12.x | Framework |
| PHPUnit | 11.x | Execução de testes |
| SQLite | 3.x | Banco de dados de teste |
| Faker | 1.x | Geração de dados fictícios |

### 5.2 Arquivos de Teste

**Testes Unitários** (`tests/Unit/`):
| Arquivo | Modelo | Testes |
|---|---|---|
| `UserTest.php` | User | 13 testes |
| `ProductTest.php` | Product | 10 testes |
| `OrderTest.php` | Order | 11 testes |
| `CategoryTest.php` | Category | 4 testes |
| `AddressTest.php` | Address | 4 testes |

**Testes de Integração** (`tests/Feature/`):
| Arquivo | Área | Testes |
|---|---|---|
| `AuthorizationTest.php` | Autorização multi-role | 10 testes |
| `CartTest.php` | Carrinho de compras | 7 testes |
| `OrderStatusWorkflowTest.php` | Workflow de pedidos (loja) | 5 testes |
| `AdminCategoryTest.php` | CRUD categorias (admin) | 6 testes |
| `ShopProductTest.php` | CRUD produtos (loja) | 5 testes |
| `AddressTest.php` | CRUD endereços (cliente) | 5 testes |
| `PerformanceTest.php` | Tempo de resposta das rotas | 16 testes |
| `UsabilityTest.php` | Usabilidade (labels, feedback, validação) | 37 testes |

### 5.3 Factories

| Factory | Modelo | Estados Disponíveis |
|---|---|---|
| `UserFactory` | User | `admin()`, `shop()`, `client()`, `inactive()` |
| `CategoryFactory` | Category | `inactive()` |
| `ProductFactory` | Product | `onSale()`, `outOfStock()`, `inactive()`, `featured()` |
| `AddressFactory` | Address | `default()` |
| `OrderFactory` | Order | `confirmed()`, `preparing()`, `delivered()`, `cancelled()`, `paid()` |
| `OrderItemFactory` | OrderItem | — |

---

## 6. Procedimento de Execução

### 6.1 Executar Todos os Testes
```bash
php artisan test
```

### 6.2 Executar Apenas Unitários
```bash
php artisan test --testsuite=Unit
```

### 6.3 Executar Apenas Feature
```bash
php artisan test --testsuite=Feature
```

### 6.4 Executar Teste Específico
```bash
php artisan test --filter="NomeDoTeste"
```

### 6.5 Executar com Detalhes (Verbose)
```bash
php artisan test -v
```

---

## 7. Riscos e Mitigações

| Risco | Impacto | Mitigação |
|---|---|---|
| Diferenças SQLite vs MySQL | Médio | Queries simplificadas; evitar funções exclusivas do MySQL |
| Dados de teste inconsistentes | Alto | Uso de `RefreshDatabase` para isolamento total |
| Views com dependências externas | Baixo | AdminLTE instalado como dependência do projeto |
| Sessão compartilhada entre testes | Médio | Driver `array` garante isolamento |

---

## 8. Cronograma

| Fase | Atividade | Status |
|---|---|---|
| 1 | Criação de factories | ✅ Concluído |
| 2 | Implementação de testes unitários | ✅ Concluído |
| 3 | Implementação de testes de integração | ✅ Concluído |
| 4 | Correção de bugs encontrados | ✅ Concluído |
| 5 | Implementação de testes de performance | ✅ Concluído |
| 6 | Implementação de testes de usabilidade | ✅ Concluído |
| 7 | Execução e validação final | ✅ Concluído |
| 8 | Documentação de resultados | ✅ Concluído |

---

## 9. Aprovação

| Métrica | Resultado |
|---|---|
| Total de testes | 157 |
| Total de assertivas | 318 |
| Testes passando | 157 (100%) |
| Testes falhando | 0 (0%) |
| Tempo de execução | ~5 segundos |
| **Status** | **✅ APROVADO** |
