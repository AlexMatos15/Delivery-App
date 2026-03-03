# Plano de Manutenção — Delivery App (Multi-Loja)

## 1. Introdução

### 1.1 Objetivo
Este documento estabelece as diretrizes e procedimentos para a manutenção contínua do sistema **Delivery App**, garantindo sua longevidade, confiabilidade e evolução ordenada ao longo do tempo.

### 1.2 Escopo
O plano abrange quatro categorias de manutenção conforme a norma ISO/IEC 14764:
- **Corretiva**: Correção de defeitos
- **Adaptativa**: Adequação a mudanças de ambiente
- **Perfectiva**: Melhorias funcionais e de desempenho
- **Preventiva**: Ações proativas para evitar problemas futuros

---

## 2. Manutenção Corretiva

### 2.1 Objetivo
Identificar e corrigir defeitos que afetam o funcionamento correto do sistema.

### 2.2 Processo

```
1. Relato do Bug → 2. Triagem → 3. Reprodução → 4. Análise → 5. Correção → 6. Testes → 7. Deploy
```

### 2.3 Classificação de Severidade

| Severidade | Descrição | SLA |
|---|---|---|
| **Crítica** | Sistema indisponível ou perda de dados | 4 horas |
| **Alta** | Funcionalidade principal comprometida (pedidos, pagamentos) | 24 horas |
| **Média** | Funcionalidade secundária com defeito | 72 horas |
| **Baixa** | Problema estético ou de conveniência | Próximo release |

### 2.4 Exemplos de Correções Realizadas

| Bug | Severidade | Descrição | Resolução |
|---|---|---|---|
| Coluna `order` vs `display_order` | Alta | Inconsistência entre migration e model | Padronização para `display_order` em todos os arquivos |
| HAVING no SQLite | Média | Query incompatível com SQLite | Remoção da cláusula HAVING desnecessária |
| Rota `toggle-status` | Alta | View referenciava rota inexistente | Correção do nome da rota no template |
| `authorize()` ausente | Alta | Trait `AuthorizesRequests` não importado no Controller base | Adição do trait ao Controller |

### 2.5 Checklist para Correção

- [ ] Bug reproduzido em ambiente de desenvolvimento
- [ ] Causa raiz identificada
- [ ] Teste automatizado criado para cobrir o cenário
- [ ] Correção implementada
- [ ] Todos os testes existentes continuam passando (`php artisan test`)
- [ ] Code review realizado
- [ ] Deploy via procedimento padrão

---

## 3. Manutenção Adaptativa

### 3.1 Objetivo
Adaptar o sistema a mudanças no ambiente operacional (versões de software, infraestrutura, requisitos legais).

### 3.2 Cenários Previstos

#### 3.2.1 Atualização do PHP
| Item | Atual | Próxima |
|---|---|---|
| PHP | 8.2.12 | 8.3+ / 8.4+ |

**Procedimento**:
1. Verificar compatibilidade em `composer.json` (`"php": "^8.2"`)
2. Executar `composer update` em ambiente de staging
3. Rodar suíte de testes completa
4. Verificar logs de depreciação
5. Atualizar `composer.json` se necessário

#### 3.2.2 Atualização do Laravel
| Item | Atual | Próximo |
|---|---|---|
| Laravel | 12.x | 13.x (quando disponível) |

**Procedimento**:
1. Consultar [guia de upgrade oficial](https://laravel.com/docs/upgrade)
2. Executar `composer update` gradualmente
3. Verificar breaking changes em middleware, routes, models
4. Rodar suíte de testes completa
5. Ajustar código conforme necessário

#### 3.2.3 Mudanças de Infraestrutura
| Cenário | Ação |
|---|---|
| XAMPP → Docker | Criar `Dockerfile` + `docker-compose.yml` |
| MySQL → PostgreSQL | Testar migrations; ajustar queries raw se houver |
| Deploy em nuvem (AWS/DigitalOcean) | Configurar `.env`, storage, queue workers |

#### 3.2.4 Conformidade Legal (LGPD)
- Implementar política de exclusão de dados pessoais sob demanda
- Adicionar página de termos de uso e política de privacidade
- Registrar consentimento do usuário no cadastro
- Criptografar dados sensíveis em repouso (endereços, contatos)

---

## 4. Manutenção Perfectiva

### 4.1 Objetivo
Adicionar novas funcionalidades e melhorar funcionalidades existentes para aumentar o valor do sistema.

### 4.2 Roadmap de Evolução

#### Curto Prazo (1-3 meses)

| Funcionalidade | Prioridade | Descrição |
|---|---|---|
| Gateway de Pagamento | Alta | Integração com Stripe/MercadoPago para checkout real |
| Notificações por E-mail | Alta | Alertas de status do pedido para clientes |
| Busca Avançada | Média | Pesquisa de produtos com filtros por preço, categoria, avaliação |
| Histórico de Preços | Baixa | Rastrear alterações de preço dos produtos |

#### Médio Prazo (3-6 meses)

| Funcionalidade | Prioridade | Descrição |
|---|---|---|
| API REST | Alta | Endpoints para aplicativo mobile |
| Avaliação de Pedidos | Média | Clientes avaliam produtos e lojas (1-5 estrelas) |
| Cupons de Desconto | Média | Sistema de cupons com regras de validade |
| Dashboard Analytics | Média | Gráficos e relatórios avançados para lojas |

#### Longo Prazo (6-12 meses)

| Funcionalidade | Prioridade | Descrição |
|---|---|---|
| App Mobile | Alta | Aplicativo React Native ou Flutter |
| Rastreamento em Tempo Real | Média | WebSocket para acompanhar entregador |
| Programa de Fidelidade | Baixa | Pontos e recompensas para clientes |
| Multi-idioma (i18n) | Baixa | Suporte a português e inglês |

### 4.3 Procedimento para Novas Funcionalidades

1. **Especificação**: Definir requisitos funcionais e não-funcionais
2. **Design**: Modelar banco de dados, rotas e interfaces
3. **Branch**: Criar branch feature (`feature/nome-da-funcionalidade`)
4. **Implementação**: Seguir padrões existentes (controllers, models, views)
5. **Testes**: Criar testes unitários e de integração
6. **Review**: Code review por outro desenvolvedor
7. **Merge**: Merge para branch principal após aprovação
8. **Deploy**: Seguir procedimento de deploy padrão

---

## 5. Manutenção Preventiva

### 5.1 Objetivo
Executar ações proativas para manter a saúde e o desempenho do sistema.

### 5.2 Tarefas Periódicas

#### Diárias
- [ ] Verificar logs de erro (`storage/logs/laravel.log`)
- [ ] Monitorar tempo de resposta do servidor
- [ ] Verificar fila de jobs pendentes

#### Semanais
- [ ] Executar suíte de testes completa (`php artisan test`)
- [ ] Limpar cache de views e configuração (`php artisan optimize:clear`)
- [ ] Verificar espaço em disco do storage

#### Mensais
- [ ] Atualizar dependências não-críticas (`composer update --minor-only`)
- [ ] Revisar logs de depreciação do PHP/Laravel
- [ ] Backup completo do banco de dados
- [ ] Revisar permissões de arquivos e diretórios

#### Trimestrais
- [ ] Auditoria de segurança das dependências (`composer audit`)
- [ ] Revisão de queries lentas (slow query log)
- [ ] Limpeza de registros antigos (soft-deletes, logs)
- [ ] Atualização de certificados SSL

### 5.3 Monitoramento

| Aspecto | Ferramenta Sugerida |
|---|---|
| Erros em Produção | Laravel Telescope / Sentry |
| Performance | Laravel Debugbar / New Relic |
| Uptime | UptimeRobot / Pingdom |
| Logs | Laravel Pail / Papertrail |

### 5.4 Backup

| Componente | Frequência | Retenção |
|---|---|---|
| Banco de Dados (MySQL) | Diário | 30 dias |
| Storage (imagens) | Semanal | 90 dias |
| Código-fonte | Contínuo (Git) | Ilimitado |
| Configuração (`.env`) | A cada mudança | Versionado separadamente |

---

## 6. Padrões de Código

### 6.1 Convenções

| Aspecto | Padrão |
|---|---|
| PHP | PSR-12 (formatação) + PSR-4 (autoloading) |
| Nomenclatura | camelCase (variáveis/métodos), PascalCase (classes), snake_case (banco/rotas) |
| Commits | Conventional Commits (`feat:`, `fix:`, `docs:`, `refactor:`) |
| Branches | `main`, `develop`, `feature/*`, `bugfix/*`, `hotfix/*` |

### 6.2 Estrutura de Diretórios

```
app/
├── Http/Controllers/      # Controllers organizados por role (Admin/, Loja/, Client/)
├── Models/                 # Modelos Eloquent
├── Policies/               # Políticas de autorização
├── Providers/              # Service providers
└── View/                   # View components e composers
database/
├── factories/              # Factories para testes
├── migrations/             # Migrations em ordem cronológica
└── seeders/                # Seeders para dados de demonstração
docs/                       # Documentação do projeto
resources/views/            # Templates Blade organizados por role
tests/
├── Unit/                   # Testes de modelo/lógica
└── Feature/                # Testes de integração HTTP
```

---

## 7. Gestão de Dependências

### 7.1 Dependências Principais

| Pacote | Versão | Função |
|---|---|---|
| `laravel/framework` | ^12.0 | Framework principal |
| `laravel/breeze` | ^2.0 | Autenticação |
| `jeroennoten/laravel-adminlte` | ^3.0 | Template admin |
| `tailwindcss` | ^4.0 | Estilização frontend |

### 7.2 Procedimento de Atualização

```bash
# 1. Verificar vulnerabilidades
composer audit

# 2. Atualizar dependências menores
composer update --minor-only

# 3. Executar testes
php artisan test

# 4. Se tudo passar, commitar
git add composer.lock
git commit -m "chore: update dependencies"
```

---

## 8. Procedimento de Deploy

### 8.1 Checklist Pre-Deploy

- [ ] Todos os testes passando (`php artisan test`)
- [ ] Verificar `.env` de produção
- [ ] Migrations pendentes identificadas
- [ ] Assets compilados (`npm run build`)

### 8.2 Passos de Deploy

```bash
# 1. Manutenção
php artisan down

# 2. Atualizar código
git pull origin main

# 3. Dependências
composer install --no-dev --optimize-autoloader

# 4. Migrations
php artisan migrate --force

# 5. Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Assets
npm ci && npm run build

# 7. Voltar ao ar
php artisan up
```

### 8.3 Rollback

```bash
# Em caso de falha
php artisan down
git checkout HEAD~1
composer install --no-dev
php artisan migrate:rollback
php artisan up
```

---

## 9. Contatos e Responsabilidades

| Papel | Responsabilidade |
|---|---|
| Desenvolvedor Backend | Controllers, Models, Migrations, Testes |
| Desenvolvedor Frontend | Views Blade, CSS/Tailwind, JavaScript |
| DBA | Backup, otimização de queries, migrations |
| DevOps | Deploy, monitoramento, infraestrutura |

---

## 10. Histórico de Revisões

| Data | Versão | Descrição |
|---|---|---|
| 2025-06-01 | 1.0 | Versão inicial do plano de manutenção |
