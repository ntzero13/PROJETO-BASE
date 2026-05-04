# Sistema de Vistorias

Ultima revisao: 04/05/2026

## 1. O que o sistema faz

O sistema e uma base SaaS para empresas que fazem vistorias. Ele separa a administracao geral da plataforma do ambiente usado por cada empresa cliente.

Na pratica, existem dois lados:

- painel central, usado pelo dono da plataforma;
- painel da empresa, usado pelo cliente que registra clientes, locais, vistorias e anexos.

A base foi montada pensando em isolamento por empresa. Cada tenant tem seu proprio banco de dados, criado a partir do slug da empresa no padrao `tenant_{slug}`.

## 2. Tecnologias usadas

- Laravel 13
- PHP 8.3
- Filament 5
- PostgreSQL
- `stancl/tenancy`
- Vite para build dos assets

O projeto esta organizado para rodar localmente pelo Laragon, usando PostgreSQL para o banco central e para os bancos das empresas.

## 3. Acessos locais

Painel central:

```text
http://admin.sistema.test/admin
```

Painel de uma empresa:

```text
http://empresa-teste.localhost:8000/admin
```

Usuario tenant de demonstracao:

```text
E-mail: admin@empresa-teste.test
Senha: password
```

Usuario central de demonstracao:

```text
E-mail: admin@sistema.test
Senha: password
```

Essas credenciais sao apenas para ambiente local. Antes de publicar qualquer ambiente real, a senha precisa ser trocada.

## 4. Como a separacao multiempresa funciona

O banco central guarda os dados da plataforma:

- planos;
- empresas cadastradas;
- dominios;
- usuarios administrativos centrais.

Os dados operacionais ficam no banco da propria empresa:

- usuarios da empresa;
- clientes;
- locais vistoriados;
- vistorias;
- comodos;
- itens;
- anexos;
- configuracoes da empresa.

O provisionamento de uma empresa passa pelo servico `ProvisionTenantService`. Esse servico cria o registro central, vincula o dominio e cria o primeiro usuario administrador dentro do banco do tenant.

O nome do banco e gerado a partir do slug. Exemplo:

```text
empresa-teste -> tenant_empresa_teste
```

## 5. Painel central

O painel central fica em `/admin` nos dominios definidos em `CENTRAL_DOMAINS`.

Hoje ele possui:

- visao geral;
- gestao de planos;
- listagem e edicao basica de empresas.

A criacao manual de empresa pelo painel central foi restringida para evitar cadastro sem banco, sem dominio ou sem usuario inicial. O caminho correto e passar pelo servico de provisionamento.

## 6. Painel da empresa

O painel da empresa tambem usa `/admin`, mas sempre no dominio do tenant.

Recursos disponiveis hoje:

- clientes;
- locais vistoriados;
- vistorias;
- anexos;
- configuracoes da empresa.

Os anexos usam armazenamento local privado por padrao. A troca para S3 ou outro storage externo deve ser feita quando o fluxo de laudo e arquivos estiver fechado.

## 7. Regras de vistoria

A vistoria pode passar por status como:

- rascunho;
- em andamento;
- finalizada;
- cancelada.

Quando uma vistoria esta finalizada, o sistema bloqueia alteracao e exclusao da propria vistoria.

O mesmo bloqueio foi aplicado aos dados relacionados:

- comodos;
- itens;
- anexos.

Essa protecao fica no backend, nao apenas na interface. Mesmo que alguem tente alterar por outro caminho, o model impede a operacao.

## 8. Testes existentes

A suite atual cobre dois pontos importantes:

- bloqueio de alteracao/exclusao de vistoria finalizada e seus dados relacionados;
- provisionamento de tenant e isolamento real entre bancos.

Comando:

```bash
php artisan test
```

Ultima execucao validada:

```text
12 testes passaram
23 assertions
```

Tambem foram validados:

```bash
php artisan route:list
npm run build
```

## 9. Rotina local recomendada

Depois de clonar ou atualizar o projeto:

```bash
composer install
npm install
php artisan key:generate
php artisan migrate
npm run build
php artisan test
```

Para limpar cache apos mudanca em configuracao, rotas ou Filament:

```bash
php artisan optimize:clear
```

## 10. Pontos que ainda faltam antes de producao

Antes de usar em cliente real, ainda falta fechar:

- permissoes por perfil dentro da empresa;
- validacao completa de acesso sem login;
- fluxo de assinatura/finalizacao formal;
- geracao de laudo em PDF;
- fila para geracao de PDF pesado;
- politica de backup por tenant;
- politica de privacidade/LGPD;
- validacao do fluxo em navegador e celular;
- storage definitivo para anexos e laudos.

## 11. Cuidados de seguranca

Nao publicar ambiente real com:

- `APP_DEBUG=true`;
- senha padrao;
- banco sem backup;
- dominio tenant mal configurado;
- storage publico para anexos privados;
- usuario administrador compartilhado por varias pessoas.

Para producao, o minimo esperado e:

- `APP_DEBUG=false`;
- `SESSION_ENCRYPT=true`;
- HTTPS ativo;
- senha forte para administradores;
- backups testados;
- filas configuradas para tarefas pesadas;
- logs sem exposicao de dados sensiveis.

## 12. Observacoes para manutencao

Nao renomear tabelas, colunas, resources ou models apenas para traduzir o codigo. Boa parte desses nomes segue convencao do Laravel, Filament e do pacote de tenancy.

Quando for necessario traduzir algo, priorizar:

- labels do Filament;
- mensagens de validacao;
- nomes de acoes;
- documentacao;
- textos exibidos ao usuario.

Nomes estruturais devem ser alterados apenas quando houver motivo tecnico claro, porque renomear contrato de banco ou rota pode quebrar migrations, testes e dados ja criados.
