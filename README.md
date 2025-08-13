# Keijiban (Spec-Only Repo) — Laravel + Next

このリポジトリは **仕様とエージェント向けの手順だけ**を収録した「設計リポ」です。  
エージェント（Copilot/Codex/Sonnetなど）は本リポを読み、下記ドキュメントに従って**このリポ内にコードと構成を新規作成**します。

- バックエンド: Laravel 11 / Sanctum / PostgreSQL / Redis
- フロントエンド: Next.js 14+ (App Router)
- コンテナ: Laravel Sail
- CI: GitHub Actions（Secrets不要、サービスコンテナ使用）

👉 まずは `docs/agent/AGENT_OVERVIEW.md` と `docs/agent/TASKS_BOOTSTRAP.md` を読んでください。

## Usage

### Backend
```bash
cd backend-laravel
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan test
```

CI では Sail を使わず、ネイティブ PHP + Postgres サービスで高速にテストしています。
Redis を無効化したい場合は `.env` で `CACHE_STORE=array` `SESSION_DRIVER=array` `QUEUE_CONNECTION=sync` に切り替えてください。
CI は高速な `test` ジョブと、Redis サービスを起動して `--group=redis` のみ実行する `test-redis` ジョブに分かれます。`test-redis` を動かす際は、PHP に `redis` 拡張を入れて `REDIS_CLIENT=phpredis` を指定するか、`composer require predis/predis` した上で `REDIS_CLIENT=predis` を指定してください。

Horizon を試す場合は `php artisan horizon` (Sailなら `./vendor/bin/sail artisan horizon`) を実行します。デフォルトではダッシュボードは無効になっています。ローカルで閲覧する場合は `HORIZON_ALLOW_DASHBOARD=true` を設定してください。

### Frontend
```bash
cd frontend
npm install
npm run dev
```
