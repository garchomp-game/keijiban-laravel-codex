# CI Guide (GitHub Actions)

Laravel + Postgres + Redis の最小構成。Secrets 不要・サービスコンテナで完結。

```yaml
name: backend-laravel

on:
  pull_request:
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
      DB_CONNECTION: pgsql
      DB_HOST: localhost
      DB_PORT: 5432
      DB_DATABASE: app
      DB_USERNAME: postgres
      DB_PASSWORD: postgres
      CACHE_STORE: redis
      QUEUE_CONNECTION: redis
      SESSION_DRIVER: cookie
      APP_ENV: testing
      APP_KEY: base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= # テスト用ダミー

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          coverage: none
          tools: composer

      - name: Install dependencies
        working-directory: backend-laravel
        run: composer install --no-interaction --prefer-dist

      - name: Generate key (if not set)
        working-directory: backend-laravel
        run: php -r "file_exists('.env')||copy('.env.example','.env');" && php artisan key:generate --force || true

      - name: Migrate
        working-directory: backend-laravel
        run: php artisan migrate --force

      - name: Run tests
        working-directory: backend-laravel
        run: php artisan test --parallel

      - name: Lint
        working-directory: backend-laravel
        run: |
          composer require --dev laravel/pint friendsofphp/php-cs-fixer -W
          ./vendor/bin/pint --test
```
