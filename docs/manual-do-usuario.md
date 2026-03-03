# Manual do Usuário — Delivery App (Multi-Loja)

## 1. Visão Geral

O **Delivery App** é um sistema web de delivery multi-loja que conecta clientes a diversas lojas em uma única plataforma. O sistema possui três tipos de usuários:

- **Cliente**: Navega por lojas, adiciona produtos ao carrinho, faz pedidos e acompanha entregas.
- **Loja**: Gerencia produtos, recebe pedidos e atualiza status de entregas.
- **Administrador**: Gerencia usuários, categorias, produtos e monitora toda a operação.

---

## 2. Acesso ao Sistema

### 2.1 Registro

1. Acesse a página inicial do sistema.
2. Clique em **"Registrar"** no menu superior.
3. Preencha: nome, e-mail, senha e confirmação de senha.
4. Clique em **"Registrar"**.
5. Verifique seu e-mail e clique no link de verificação.
6. Após verificação, você será redirecionado para a página inicial.

> **Nota**: Por padrão, novos registros criam contas do tipo **cliente**. Contas de loja e admin são criadas pelo administrador.

### 2.2 Login

1. Acesse a página inicial.
2. Clique em **"Entrar"**.
3. Informe e-mail e senha.
4. Clique em **"Entrar"**.
5. Você será redirecionado ao dashboard correspondente ao seu tipo de conta.

### 2.3 Recuperação de Senha

1. Na tela de login, clique em **"Esqueceu sua senha?"**.
2. Informe o e-mail cadastrado.
3. Verifique sua caixa de entrada e clique no link de recuperação.
4. Defina uma nova senha.

---

## 3. Guia do Cliente

### 3.1 Página Inicial (Home)

Após o login, o cliente vê a **Home** com:
- Lista de lojas disponíveis
- Produtos em destaque (featured)
- Opção de selecionar uma loja para navegar

### 3.2 Navegando por Produtos

1. Selecione uma loja na página inicial ou acesse **"Produtos"**.
2. Use os **filtros por categoria** para encontrar o que deseja.
3. Use a **barra de busca** para pesquisar por nome.
4. Clique em um produto para ver detalhes (descrição, preço, disponibilidade).

### 3.3 Carrinho de Compras

#### Adicionar Produto
1. Na página do produto, defina a **quantidade** desejada.
2. Clique em **"Adicionar ao Carrinho"**.
3. O ícone do carrinho no menu será atualizado com a contagem.

> **Atenção**: Você só pode adicionar produtos de **uma loja por vez**. Ao adicionar um produto de outra loja, o carrinho anterior será substituído.

#### Visualizar Carrinho
1. Clique no ícone do **carrinho** no menu superior.
2. Veja a lista de produtos, quantidades e preços.
3. Altere quantidades ou remova itens conforme necessário.

#### Atualizar Quantidade
1. No carrinho, altere o número no campo de quantidade.
2. Clique no botão de **atualizar**.

#### Remover Produto
1. No carrinho, clique no botão **"Remover"** ao lado do produto.

#### Limpar Carrinho
1. Clique em **"Limpar Carrinho"** para remover todos os itens.

### 3.4 Endereços de Entrega

#### Cadastrar Endereço
1. Acesse **"Meus Endereços"** no menu ou perfil.
2. Clique em **"Novo Endereço"**.
3. Preencha os campos:
   - **Rótulo** (ex: "Casa", "Trabalho")
   - **Rua** e **Número**
   - **Complemento** (opcional)
   - **Bairro**, **Cidade**, **Estado**
   - **CEP**
   - **Ponto de Referência** (opcional)
4. Clique em **"Salvar"**.

#### Editar Endereço
1. Na lista de endereços, clique em **"Editar"** no endereço desejado.
2. Altere os campos necessários.
3. Clique em **"Salvar"**.

#### Definir Endereço Padrão
1. Na lista de endereços, clique em **"Tornar Padrão"** no endereço desejado.
2. Esse endereço será pré-selecionado no checkout.

#### Excluir Endereço
1. Na lista de endereços, clique em **"Excluir"**.
2. Confirme a exclusão.

> **Nota**: Se você tiver pedidos vinculados, é necessário manter pelo menos um endereço.

### 3.5 Finalizando um Pedido (Checkout)

1. No carrinho, clique em **"Finalizar Pedido"**.
2. Selecione o **endereço de entrega** (ou cadastre um novo).
3. Escolha o **método de pagamento**:
   - Cartão de Crédito
   - Cartão de Débito
   - PIX
   - Dinheiro
4. Revise o resumo do pedido:
   - Itens e quantidades
   - Subtotal
   - Taxa de entrega (R$ 5,00)
   - **Total**
5. Clique em **"Confirmar Pedido"**.
6. Você será redirecionado para a **página de confirmação**.

### 3.6 Acompanhando Pedidos

#### Listar Pedidos
1. Acesse **"Meus Pedidos"** no menu.
2. Veja a lista com número do pedido, data, status e valor.

#### Status do Pedido

| Status | Significado |
|---|---|
| 🟡 Pendente | Pedido recebido, aguardando confirmação da loja |
| 🔵 Confirmado | Loja aceitou o pedido |
| 🟠 Preparando | Pedido sendo preparado |
| 🚚 Saiu para Entrega | Pedido a caminho |
| ✅ Entregue | Pedido concluído |
| ❌ Cancelado | Pedido foi cancelado |

#### Ver Detalhes
1. Clique em um pedido para ver:
   - Itens com nome, quantidade e preço
   - Endereço de entrega
   - Método de pagamento
   - Timeline de status

#### Cancelar Pedido
1. Nos detalhes do pedido, clique em **"Cancelar Pedido"**.
2. Confirme o cancelamento.

> **Importante**: Só é possível cancelar pedidos com status **Pendente** ou **Confirmado**. Pedidos em preparação ou em entrega não podem ser cancelados.

### 3.7 Perfil

1. Acesse **"Meu Perfil"** no menu.
2. Visualize seus dados (nome, e-mail, tipo de conta).
3. Na tela de edição, você pode:
   - Alterar nome e e-mail
   - Alterar senha (requer senha atual)

---

## 4. Guia da Loja

### 4.1 Dashboard

Após o login, a loja vê o dashboard com:
- Total de pedidos
- Pedidos pendentes
- Pedidos em processamento
- Pedidos concluídos
- Botão para abrir/fechar a loja

### 4.2 Gerenciamento de Produtos

#### Listar Produtos
1. No menu lateral, acesse **"Produtos"**.
2. Veja a lista com nome, preço, estoque e status.

#### Criar Produto
1. Clique em **"Novo Produto"**.
2. Preencha:
   - **Nome** do produto
   - **Descrição**
   - **Categoria** (existente ou criar nova)
   - **Preço** (R$)
   - **Preço Promocional** (opcional, menor que o preço regular)
   - **Estoque** (quantidade disponível)
   - **Imagem** (opcional, até 2MB)
   - **Destaque** (marcar como featured)
3. Clique em **"Salvar"**.

#### Editar Produto
1. Na lista de produtos, clique em **"Editar"**.
2. Altere os campos desejados.
3. Clique em **"Salvar"**.

#### Ativar/Desativar Produto
1. Na lista de produtos, clique no botão de **toggle** de status.
2. Produtos inativos não aparecem para os clientes.

### 4.3 Gerenciamento de Pedidos

#### Listar Pedidos
1. No menu lateral, acesse **"Pedidos"**.
2. Veja os pedidos organizados por status com dados do cliente.

#### Ver Detalhes do Pedido
1. Clique em um pedido para ver:
   - Dados do cliente
   - Itens do pedido
   - Endereço de entrega
   - Valor total

#### Atualizar Status do Pedido
1. Nos detalhes do pedido, use os botões de ação para avançar o status:
   - **Confirmar** (pendente → confirmado)
   - **Preparar** (confirmado → preparando)
   - **Enviar** (preparando → saiu para entrega)
   - **Concluir** (saiu para entrega → entregue)
   - **Cancelar** (em qualquer status antes de entregue)

> **Fluxo**: Pendente → Confirmado → Preparando → Saiu para Entrega → Entregue

### 4.4 Relatórios

1. No menu lateral, acesse **"Relatórios"**.
2. Visualize:
   - Dashboard geral de vendas
   - Relatório de vendas por período
   - Relatório de clientes

---

## 5. Guia do Administrador

### 5.1 Dashboard

O dashboard do administrador exibe:
- Total de usuários, lojas e pedidos
- Receita total
- Pedidos recentes
- Produtos mais vendidos

### 5.2 Gerenciamento de Usuários

#### Listar Usuários
1. No menu lateral, acesse **"Usuários"**.
2. Veja a lista com nome, e-mail, tipo e status.

#### Ativar/Desativar Usuário
1. Na lista, clique no botão de **toggle** para ativar ou desativar.
2. Usuários inativos são deslogados automaticamente.

#### Excluir Usuário
1. Na lista, clique em **"Excluir"**.
2. Confirme a exclusão.

> **Atenção**: A exclusão é permanente. Prefira desativar em vez de excluir.

### 5.3 Gerenciamento de Categorias

#### Listar Categorias
1. No menu lateral, acesse **"Categorias"**.
2. Veja a lista com nome, slug, ordem e status.

#### Criar Categoria
1. Clique em **"Nova Categoria"**.
2. Preencha:
   - **Nome** da categoria
   - **Descrição** (opcional)
   - **Ordem de Exibição** (número para ordenação)
   - **Imagem** (opcional, até 2MB)
   - **Ativa** (checkbox)
3. Clique em **"Salvar"**.

> O **slug** é gerado automaticamente a partir do nome (ex: "Pizzas Salgadas" → `pizzas-salgadas`).

#### Editar Categoria
1. Na lista, clique em **"Editar"**.
2. Altere os campos desejados.
3. Clique em **"Salvar"**.

#### Ativar/Desativar Categoria
1. Na lista, clique no botão de **toggle**.
2. Categorias inativas não aparecem no catálogo público.

#### Excluir Categoria
1. Na lista, clique em **"Excluir"**.
2. Confirme a exclusão.

### 5.4 Gerenciamento de Produtos

O administrador pode gerenciar produtos de todas as lojas:

1. No menu lateral, acesse **"Produtos"**.
2. As operações são similares às da loja (criar, editar, ativar/desativar).

### 5.5 Gerenciamento de Pedidos

1. No menu lateral, acesse **"Pedidos"**.
2. Visualize todos os pedidos do sistema.
3. Filtre por status, loja ou período.
4. Atualize status ou pagamento conforme necessário.

---

## 6. Perguntas Frequentes (FAQ)

### Para Clientes

**P: Posso comprar de várias lojas ao mesmo tempo?**
R: Não. O carrinho aceita produtos de apenas uma loja por vez. Ao adicionar um produto de outra loja, o carrinho anterior é substituído.

**P: Posso cancelar meu pedido?**
R: Sim, mas apenas se o pedido estiver com status "Pendente" ou "Confirmado". Após o início da preparação, o cancelamento não é possível.

**P: Como sei que meu pedido foi aceito?**
R: O status do pedido muda de "Pendente" para "Confirmado" quando a loja aceita. Acompanhe em "Meus Pedidos".

**P: Qual é a taxa de entrega?**
R: A taxa de entrega é fixa em R$ 5,00 por pedido.

### Para Lojas

**P: Como recebo novos pedidos?**
R: Novos pedidos aparecem automaticamente na lista de pedidos com status "Pendente". Acesse regularmente para verificar.

**P: Posso recusar um pedido?**
R: Sim, ao visualizar um pedido pendente, use a opção de "Cancelar" para recusá-lo.

**P: O estoque é atualizado automaticamente?**
R: Sim. Ao confirmar um pedido, o estoque dos produtos é decrementado automaticamente. Se o pedido for cancelado, o estoque é restaurado.

### Para Administradores

**P: Como crio uma conta de loja?**
R: Atualmente, o tipo de conta é definido durante a criação. Use o seeder para criar lojas de teste ou altere o tipo diretamente no banco de dados.

**P: O que acontece quando desativo um usuário?**
R: O usuário é deslogado automaticamente na próxima requisição e não poderá fazer login até ser reativado.

---

## 7. Atalhos e Dicas

| Dica | Descrição |
|---|---|
| Endereço padrão | Defina um endereço padrão para agilizar o checkout |
| Produtos em destaque | Lojas podem marcar produtos como "destaque" para aparecerem na home |
| Preço promocional | Defina um preço promocional menor que o regular para atrair clientes |
| Fechar loja | Lojas podem desativar temporariamente via botão no dashboard |

---

## 8. Requisitos do Sistema

| Requisito | Especificação |
|---|---|
| Navegador | Chrome 90+, Firefox 88+, Safari 14+, Edge 90+ |
| JavaScript | Habilitado |
| Cookies | Habilitados (necessários para sessão) |
| Resolução Mínima | 320px (responsivo) |
| Conexão | Internet banda larga |
