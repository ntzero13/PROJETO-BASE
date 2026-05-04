# Base SaaS Vistorias

Base Laravel para sistemas SaaS de vistorias, com separação entre painel central e painel da empresa.

## Estrutura

- Laravel 13
- Filament 5
- PostgreSQL
- `stancl/tenancy`
- Painel central em `/admin` nos domínios centrais
- Painel da empresa em `/admin` no domínio do tenant
- Banco central separado dos bancos das empresas

## Ambientes Locais

O projeto usa PostgreSQL local. Configure o `.env` a partir de `.env.example` e ajuste os dados de conexão antes de rodar migrações.

Domínios centrais esperados no ambiente local:

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

## Painéis

Painel central:

```text
http://admin.sistema.test/admin
```

Painel da empresa:

```text
http://{subdomínio}.sistema.test/admin
```

## Observações

- Não use credenciais padrão em produção.
- Mantenha `APP_DEBUG=false` em produção.
- Gere uma `APP_KEY` própria para cada ambiente.
- Configure corretamente `CENTRAL_DOMAINS` e os domínios dos tenants antes de publicar.
