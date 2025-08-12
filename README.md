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
php artisan migrate
php artisan test
```

### Frontend
```bash
cd frontend
npm install
npm run dev
```
