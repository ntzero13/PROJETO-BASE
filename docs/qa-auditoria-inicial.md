# Auditoria inicial de QA

Data: 2026-05-04

## Escopo verificado

Projeto Laravel/Filament em estado de base SaaS multiempresa. A verificação cobriu estrutura de código, rotas, providers Filament, models, migrations, configuração, testes automatizados e comandos de build/segurança disponíveis no ambiente local.

## Evidência de execução

- `php artisan route:list`: executado com sucesso; existem rotas de login, logout, reset de senha e dashboards Filament central/tenant.
- `php artisan test`: executado com sucesso após os ajustes de QA.
- `composer validate --strict`: corrigido e executado com sucesso; `composer.lock` está sincronizado.
- `composer audit`: passou. Nenhum advisory de vulnerabilidade conhecido foi encontrado.
- `npm run build`: corrigido e executado com sucesso após instalar dependências npm locais.
- `php artisan migrate:status`: depende do PostgreSQL local ativo.
- `php artisan about`: executado com sucesso; ambiente local, debug ativo, storage público não linkado.

## Correções críticas aplicadas

- A rota `/` redireciona para `/admin/login`.
- `.env.example` usa `APP_DEBUG=false`, `LOG_LEVEL=warning`, `SESSION_ENCRYPT=true`, locale `pt_BR` e senha administrativa placeholder não trivial.
- `composer.lock` foi sincronizado.
- `package-lock.json` foi gerado e o build de produção do Vite passou.
- Foram adicionados models tenant para clientes, locais vistoriados, vistorias, cômodos, itens, anexos e configurações da empresa.
- Foram adicionados Resources Filament centrais para planos e empresas.
- Foram adicionados Resources Filament tenant para clientes, locais, vistorias, anexos e configurações.
- Tabelas novas possuem busca, ordenação e filtros nos pontos principais.
- Upload de anexos aceita imagens/PDF, limita tamanho e usa armazenamento `local` por padrão.
- Ações de editar/excluir vistorias finalizadas foram ocultadas no painel.
- O model de vistoria bloqueia alteração/exclusão quando o status original já está `finalizada`.
- Cômodos, itens e anexos também respeitam o bloqueio da vistoria finalizada.
- A criação manual de tenants pelo painel central ficou indisponível para evitar empresa sem banco/domínio provisionado.
- Foram adicionados testes de provisionamento e isolamento real entre bancos tenant.

## Funcionalidades existentes

- Login central via Filament.
- Login tenant via Filament.
- Logout central/tenant via Filament.
- Recuperação de senha central/tenant via Filament.
- Dashboards simples central e tenant.
- Separação de guards/providers: `central` e `tenant`.
- Migrations centrais para planos, tenants e domínios.
- Migrations tenant para usuários, clientes, locais vistoriados, vistorias, cômodos, itens, anexos e configurações da empresa.
- Serviço de provisionamento de tenant com criação de domínio e usuário inicial.
- CRUD administrativo central para planos e visualização/edição de empresas.
- CRUD tenant inicial para clientes, locais, vistorias, anexos e configurações.

## Funcionalidades não implementadas ainda

- Cadastro público de usuário.
- Geração de PDF/relatórios.
- Assinatura/finalização formal de vistoria.
- Policies completas para permissões por perfil.
- Regras de permissão por tipo de usuário além de `is_active` e separação de painel.
- Fluxo completo: criar vistoria, anexar fotos, salvar, assinar, gerar PDF e bloquear edição.

## Resultado por bloco de teste

### 1. Testes funcionais

Status geral: parcialmente aprovado.

- Login/logout: implementado pelo Filament, mas ainda precisa de validação visual completa em navegador com banco ativo.
- Recuperação de senha: rotas existem, mas o envio/fluxo completo ainda não foi validado.
- CRUD, filtros, busca, ordenação e upload básico: implementados para os recursos principais.
- PDF e relatórios: ainda não implementados.
- Mensagens de erro/sucesso e persistência visual: parcialmente cobertas pelos formulários Filament.

### 2. Regras de negócio

Status geral: parcialmente aprovado.

- Existe CRUD inicial de vistoria.
- A tela oculta editar/excluir quando a vistoria está `finalizada`.
- O backend bloqueia alteração/exclusão da vistoria finalizada.
- O backend bloqueia alteração/exclusão de cômodos, itens e anexos vinculados a vistoria finalizada.
- Ainda faltam assinatura, geração de laudo e fluxo final de fechamento.

### 3. Segurança

Status geral: parcialmente aprovado na arquitetura, ainda pendente para produção.

- Rotas Filament usam middleware de autenticação.
- Painel tenant usa `PreventAccessFromCentralDomains` e `InitializeTenancyByDomain`.
- Senhas usam cast `hashed` nos models central e tenant.
- `composer audit` não encontrou advisories.
- `APP_DEBUG`, senha padrão fraca e sessão sem criptografia foram corrigidos no `.env.example`.
- A migration de anexos usa disk `local` por padrão, evitando publicação direta de arquivos privados.
- Ainda faltam testes automatizados de acesso sem login, CSRF, XSS, SQL injection e upload inválido.

### 4. Usabilidade

Status geral: parcialmente aprovado.

- A rota `/` conduz ao login do painel central.
- Dashboards existem, mas ainda têm pouco conteúdo operacional.
- Menus e formulários principais foram adicionados para o painel tenant.
- Fluxo guiado e validação mobile ainda não existem.

### 5. Interface visual

Status geral: parcialmente existente.

- Filament fornece base visual consistente para login/dashboard.
- Existem telas de negócio iniciais para validar tabelas, modais e formulários.
- Labels principais foram revisados para português.

### 6. Validação de formulário

Status geral: parcial.

- Existem formulários iniciais para clientes, locais, vistorias, anexos e configurações, com obrigatórios básicos, e-mail, numéricos, upload e tamanho de arquivo.
- Validações específicas de CPF/CNPJ, placa e chassi ainda dependem da decisão final do domínio do produto.
- Login/reset usam validações internas do Filament.

### 7. Performance

Status geral: não testável ainda.

- Não há dados massivos, listagens pesadas, PDFs, uploads grandes ou simulação de usuários.
- Antes desse bloco, é necessário implementar CRUDs finais e semear massa de teste.

### 8. Compatibilidade

Status geral: não testado.

- Ainda falta validação real em Chrome, Edge, Firefox, Android e iPhone.
- Falta runtime completo com banco e build frontend em ambiente persistente.

### 9. Permissões e multiempresa

Status geral: arquitetura aprovada, permissões ainda parciais.

- Há separação central/tenant por guard, provider e domínio.
- Há model `Tenant`, `Domain` e banco por tenant via `stancl/tenancy`.
- Testes automatizados provam que dados gravados no tenant A não aparecem no tenant B.
- Admin global possui telas para planos e empresas.
- Falta usuário comum com perfil restrito.

### 10. Banco de dados

Status geral: estrutura inicial aprovada.

- Migrations existem para entidades principais de vistoria.
- Relacionamentos básicos estão declarados por foreign keys.
- Provisionamento cria bancos no padrão `tenant_{slug}`.
- Falta validar volume real, backup e restore por tenant.

### 11. Erros e exceções

Status geral: parcial.

- Há fluxo inicial de upload/formulários, mas PDF ainda não existe para forçar exceções.
- `APP_DEBUG=false` foi aplicado no `.env.example`; a validação do ambiente real de produção ainda depende do `.env` implantado.

### 12. LGPD e privacidade

Status geral: pendente.

- O sistema deverá armazenar dados pessoais de clientes, usuários e vistorias.
- Ainda não há política de privacidade, anonimização/exclusão ou revisão de logs.
- PDFs e anexos privados precisam de decisão clara de armazenamento antes da implementação final.

## Prioridades antes de produção

1. Validar runtime completo em PostgreSQL local.
2. Implementar policies/permissões por perfil.
3. Implementar testes automatizados de acesso sem login e permissões.
4. Implementar assinatura/finalização e geração de PDF.
5. Validar fluxo principal em navegador real com banco ativo.
6. Validar HTTPS, backup e variáveis reais no ambiente de produção.
