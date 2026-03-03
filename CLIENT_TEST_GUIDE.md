# 🧪 GUIA DE TESTE - Nível Cliente Redesenhado

## 🚀 Como Testar o Novo Sistema Cliente

### Pré-requisitos
- Laravel 12 rodando em http://127.0.0.1:8000
- MySQL conectado
- Produtos cadastrados no banco (com estoque > 0)

---

## 1️⃣ Teste: Primeira Visita (Não Autenticado)

### 📍 Ir para Home
```
URL: http://127.0.0.1:8000/
```

**Esperado:**
- ✅ Ver page de produtos em destaque
- ✅ Header com logo e botões "Entrar" e "Registrar"
- ✅ Cards de produtos com imagem, nome, preço
- ✅ Botão "Entrar para Comprar" nos cards
- ✅ Visual moderno (sem AdminLTE)

### 📍 Ir para Listagem de Produtos
```
URL: http://127.0.0.1:8000/products
```

**Esperado:**
- ✅ Grid de produtos
- ✅ Filtro por categoria
- ✅ Busca por nome
- ✅ Checkbox "Destaques"
- ✅ Produtos com estoque mostrando "❌ Fora de estoque"
- ✅ Sem acesso ao carrinho (botão "Entrar")

---

## 2️⃣ Teste: Autenticação

### 📍 Registrar Novo Usuário
```
URL: http://127.0.0.1:8000/register
```

**Dados:**
- Nome: "João Cliente"
- Email: "joao@test.com"
- Senha: "password123"
- Confirme: "password123"

**Esperado:**
- ✅ Usuário criado como tipo "client" (padrão)
- ✅ Email verificado (Breeze padrão)
- ✅ Redirecionado para dashboard

### 📍 Fazer Login
```
URL: http://127.0.0.1:8000/login
```

**Dados:**
- Email: "joao@test.com"
- Senha: "password123"

**Esperado:**
- ✅ Login bem-sucedido
- ✅ Redirecionado para /
- ✅ Header atualizado (mostra carrinho e menu usuário)

---

## 3️⃣ Teste: Carrinho

### 📍 Adicionar Produto ao Carrinho
```
1. Ir para http://127.0.0.1:8000/
2. Clicar em "Adicionar" em um produto
```

**Esperado:**
- ✅ Mensagem "✓ Adicionado!" no botão
- ✅ Contador do carrinho atualiza (🛒 1)
- ✅ Página recarrega com mensagem de sucesso
- ✅ Carrinho tem 1 item

### 📍 Ver Carrinho
```
URL: http://127.0.0.1:8000/cart
```

**Esperado:**
- ✅ Produto aparece na lista
- ✅ Mostra imagem, nome, preço, quantidade
- ✅ Botões -/+ para aumentar/diminuir
- ✅ Botão "Remover"
- ✅ Resumo com Subtotal + Taxa de Entrega (R$ 5,00)
- ✅ Total correto: subtotal + 5.00
- ✅ Botão "Finalizar Pedido"

### 📍 Aumentar Quantidade
```
1. No carrinho, clicar "+" para aumentar quantidade
```

**Esperado:**
- ✅ Quantidade aumenta
- ✅ Subtotal recalcula
- ✅ Total atualiza
- ✅ Página recarrega

### 📍 Remover Produto
```
1. Clicar em "Remover"
```

**Esperado:**
- ✅ Produto remove do carrinho
- ✅ Página recarrega
- ✅ Contador do carrinho atualiza (0)
- ✅ Mensagem "Seu carrinho está vazio"

### 📍 Adicionar Múltiplos Produtos
```
1. Adicionar produto A (quantidade 2)
2. Adicionar produto B (quantidade 1)
3. Ir para carrinho
```

**Esperado:**
- ✅ Ambos produtos aparecem
- ✅ Quantidades corretas
- ✅ Subtotal é soma de todos
- ✅ Total inclui taxa

---

## 4️⃣ Teste: Checkout

### 📍 Sem Endereço Cadastrado
```
1. Novo usuário
2. Ir para carrinho
3. Clicar "Finalizar Pedido"
```

**Esperado:**
- ✅ Redirecionado para criar endereço
- ✅ Mensagem "Você precisa adicionar um endereço"

### 📍 Criar Endereço
```
URL: http://127.0.0.1:8000/addresses/create

Dados:
- Label: "Casa"
- Rua: "Rua das Flores"
- Número: "123"
- Complemento: "Apto 42"
- Bairro: "Centro"
- Cidade: "São Paulo"
- Estado: "SP"
- CEP: "01234-567"
```

**Esperado:**
- ✅ Endereço criado
- ✅ Marcado como padrão
- ✅ Redirecionado para lista

### 📍 Checkout com Endereço
```
1. Adicionar produtos ao carrinho
2. Ir para carrinho
3. Clicar "Finalizar Pedido"
4. URL: http://127.0.0.1:8000/checkout
```

**Esperado:**
- ✅ Página com 4 seções:
  1. Endereço de Entrega (Casa selecionada por padrão)
  2. Método de Pagamento (Pix, Crédito, Débito, Dinheiro)
  3. Observações (campo opcional)
  4. Resumo do Pedido (itens + total)
- ✅ Botões visíveis:
  - "← Voltar ao Carrinho"
  - "✓ Confirmar Pedido"

### 📍 Confirmar Pedido
```
1. Na página checkout, deixar padrões
2. Selecionar "Pix" como pagamento
3. Clicar "Confirmar Pedido"
```

**Esperado:**
- ✅ Redirecionado para confirmação
- ✅ URL: /checkout/confirmation/{order_id}
- ✅ Página mostra:
  - "✅ Pedido Confirmado!"
  - Número do pedido (ORD-XXXXXXXX)
  - Status "Pendente"
  - Endereço de entrega
  - Itens do pedido
  - Resumo financeiro
  - Método de pagamento
  - Próximos passos

### 📍 Verificar Estoque Decrementado
```
1. Ir para /products
2. Verificar estoque do produto que comprou
```

**Esperado:**
- ✅ Estoque reduzido da quantidade comprada
- ✅ Se quantidade era 5 e comprou 2, agora mostra 3

---

## 5️⃣ Teste: Pedidos

### 📍 Ver Histórico de Pedidos
```
URL: http://127.0.0.1:8000/orders
```

**Esperado:**
- ✅ Lista de pedidos do usuário
- ✅ Mostra número do pedido
- ✅ Mostra status "Pendente"
- ✅ Mostra total
- ✅ Link para "Ver Detalhes"

### 📍 Ver Detalhes do Pedido
```
1. Clicar em pedido na lista
URL: /orders/{order_id}
```

**Esperado:**
- ✅ Informações completas do pedido
- ✅ Itens com quantidades
- ✅ Subtotal + Taxa + Total
- ✅ Endereço de entrega
- ✅ Método de pagamento
- ✅ Botão para cancelar (se pendente)

---

## 6️⃣ Teste: Validações

### 📍 Estoque Insuficiente
```
1. Produto com estoque = 2
2. Tentar adicionar quantidade = 5
```

**Esperado:**
- ✅ Erro: "Estoque insuficiente. Disponível: 2"
- ✅ Produto não é adicionado

### 📍 Mistura de Lojas
```
1. Adicionar produto da loja A (seller_1)
2. Tentar adicionar produto da loja B (seller_2)
```

**Esperado:**
- ✅ Erro: "Seu carrinho contém itens de outra loja..."
- ✅ Produto não é adicionado
- ✅ Sugestão de limpar carrinho

### 📍 Validação de Checkout
```
1. Ir para /checkout
2. NÃO selecionar endereço
3. Clicar "Confirmar Pedido"
```

**Esperado:**
- ✅ Alert: "Selecione um endereço para entrega"
- ✅ Formulário não é enviado

### 📍 Produto Indisponível
```
1. Produto com estoque = 0
2. Tentar adicionar
```

**Esperado:**
- ✅ Botão "Indisponível" desabilitado
- ✅ Sem conseguir adicionar
- ✅ Mensagem "Fora de estoque"

---

## 7️⃣ Teste: Responsive

### 📍 Mobile (320px)
```
DevTools: iPhone SE
```

**Esperado:**
- ✅ Header mobile com menu colapsável
- ✅ Produtos em coluna única
- ✅ Formulários com largura completa
- ✅ Botões grandes e clicáveis
- ✅ Sem scroll horizontal

### 📍 Tablet (768px)
```
DevTools: iPad
```

**Esperado:**
- ✅ Grid 2 colunas de produtos
- ✅ Formulário checkout lado a lado
- ✅ Layout equilibrado

### 📍 Desktop (1200px)
```
Monitor normal
```

**Esperado:**
- ✅ Grid 4-5 colunas
- ✅ Layouts de 2 colunas (cart + summary)
- ✅ Estilo completo

---

## 8️⃣ Teste: Menu de Usuário

### 📍 Dropdown de Menu
```
1. Clicar no ícone 👤 no header
```

**Esperado:**
- ✅ Dropdown abre
- ✅ Opções:
  - Meu Perfil
  - Meus Pedidos
  - Meus Endereços
  - Sair

### 📍 Navegar Pelo Menu
```
1. Clicar "Meu Perfil"
URL: /my-profile
```

**Esperado:**
- ✅ Abre página de perfil
- ✅ Header continua visível
- ✅ Dropdown fecha automaticamente

---

## 9️⃣ Teste: Admin/Loja (Comparação)

### 📍 Login como Admin
```
Email: admin@example.com
Senha: password
URL: http://127.0.0.1:8000/admin/dashboard
```

**Esperado:**
- ✅ Usa AdminLTE (não é layout client)
- ✅ Menu de admin
- ✅ Dashboard admin

### 📍 Login como Loja
```
Email: loja@example.com
Senha: password
URL: http://127.0.0.1:8000/loja/dashboard
```

**Esperado:**
- ✅ Usa AdminLTE (não é layout client)
- ✅ Menu de loja (Painel, Pedidos, Produtos)
- ✅ Dashboard de loja

### 📍 Login como Cliente
```
Email: joao@test.com
Senha: password
URL: http://127.0.0.1:8000/
```

**Esperado:**
- ✅ Novo layout client (SEM AdminLTE)
- ✅ Visão de compras

---

## 🐛 Problemas Esperados & Soluções

### ❌ "Rotas não encontradas"
```bash
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### ❌ "CSS não está carregando"
```bash
php artisan view:clear
# Verificar se arquivo existe em public/css/client.css
```

### ❌ "Erro no Checkout"
```bash
php artisan migrate
php artisan db:seed
# Verificar se tabelas existem (orders, order_items)
```

### ❌ "Carrinho não atualiza"
```bash
# Verificar SESSION em .env
SESSION_DRIVER=file ou database
```

---

## ✅ Checklist Final

Antes de considerar completo:

- [ ] Home carrega corretamente
- [ ] Produtos aparecem com imagens
- [ ] Pode adicionar ao carrinho
- [ ] Carrinho atualiza em tempo real
- [ ] Checkout cria pedido
- [ ] Estoque decrementa
- [ ] Pedido aparece em histórico
- [ ] Responsive em mobile
- [ ] Sem erros no console
- [ ] Admin/Loja ainda funcionam normalmente
- [ ] Validações funcionam
- [ ] Transações são atômicas

---

## 🎉 Sucesso!

Se todos os testes passaram, o redesign do nível cliente está **100% funcional**!

---

## 📞 Suporte

Dúvidas? Ver:
- [CLIENT_REDESIGN_COMPLETE.md](CLIENT_REDESIGN_COMPLETE.md) - Documentação técnica
- Código fonte em `app/Http/Controllers/`
- Views em `resources/views/client/`
- Estilos em `public/css/client.css`
