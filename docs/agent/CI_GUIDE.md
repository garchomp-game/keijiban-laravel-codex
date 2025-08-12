# CI Guide (Create in This Repo)

下の内容で **このリポ**の `.github/workflows/backend-laravel.yml` を作成する。

```yaml
name: backend-laravel

on:
  pull_request:
    paths:
      - "backend-laravel/**"
      - ".github/workflows/**"
  push:
    branches: [ main ]
    paths:
      - "backend-laravel/**"
      - ".github/workflows/**"
  workflow_dispatch:

jobs:
  test:
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:16
        env:
          POSTGRES_DB: app
          POSTGRES_USER: postgres
          POSTGRES_PASSWORD: postgres
        ports: ["5432:5432"]
        options: >-
          --health-cmd="pg_isready -U postgres"
          --health-interval=10s --health-timeout=5s --health-retries=10
      redis:
        image: redis:7
        ports: ["6379:6379"]

    env:
      APP_ENV: testing
      APP_KEY: base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= # テスト用ダミー
      DB_CONNECTION: pgsql
      DB_HOST: localhost
      DB_PORT: 5432
      DB_DATABASE: app
      DB_USERNAME: postgres
      DB_PASSWORD: postgres
      CACHE_STORE: redis
      QUEUE_CONNECTION: redis
      SESSION_DRIVER: cookie

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          tools: composer
          coverage: none

      - name: Install dependencies
        working-directory: backend-laravel
        run: composer install --no-interaction --prefer-dist

      - name: Generate key
        working-directory: backend-laravel
        run: php -r "file_exists('.env')||copy('.env.example','.env');" && php artisan key:generate --force || true

      - name: Run migrations
        working-directory: backend-laravel
        run: php artisan migrate --force

      - name: Run tests
        working-directory: backend-laravel
        run: php artisan test --parallel
```
