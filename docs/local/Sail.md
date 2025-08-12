# Laravel Sail でのローカル開発

## 起動

```bash
cd backend-laravel
cp .env.example .env
composer install
./vendor/bin/sail up -d
```

## マイグレーションとテスト

```bash
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan test
```

フロントエンド(Next.js)を同時に起動する場合は、`SANCTUM_STATEFUL_DOMAINS` や `FRONTEND_URL` のポートに注意してください。CORS エラーが出る場合は `.env` を調整します。

## 停止

```bash
./vendor/bin/sail down
```
