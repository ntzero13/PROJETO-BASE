# Base SaaS Vistorias

Base Laravel para sistemas SaaS de vistorias, com separacao entre painel central e painel da empresa.

## Estrutura

- Laravel 13
- Filament 5
- PostgreSQL
- `stancl/tenancy`
- Painel central em `/admin` nos dominios centrais
- Painel da empresa em `/admin` no dominio do tenant
- Banco central separado dos bancos das empresas

## Ambientes Locais

O projeto usa PostgreSQL local. Configure o `.env` a partir de `.env.example` e ajuste os dados de conexao antes de rodar migracoes.

Dominios centrais esperados no ambiente local:

- `admin.sistema.test`
- `master.sistema.test`
- `localhost`
- `127.0.0.1`

## Comandos

```bash
php artisan migrate
php artisan test
npm install
npm run build
```

## Paineis

Painel central:

```text
http://admin.sistema.test/admin
```

Painel da empresa:

```text
http://{subdominio}.sistema.test/admin
```

## Observacoes

- Nao use credenciais padrao em producao.
- Mantenha `APP_DEBUG=false` em producao.
- Gere uma `APP_KEY` propria para cada ambiente.
- Configure corretamente `CENTRAL_DOMAINS` e os dominios dos tenants antes de publicar.
