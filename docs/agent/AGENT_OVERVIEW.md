# Agent Overview (This Repo = Source of Truth)

## 役割
あなた（エージェント）は、このリポジトリを読み、**本リポ直下**に必要なコード/構成を作成します。  
外部リポやサブモジュールは使わず、**すべてこのリポに追加**してください。

## 作成するディレクトリ（このリポ直下）
- `backend-laravel/` … Laravel 11（SailでPostgres/Redis）
- `frontend/` … Next.js 14+（App Router）
- `.github/workflows/` … CI（Laravel/PHP & Postgres/Redis）

## 原則
1. 設定より規約（Laravel標準・Sanctum・Eloquent・FormRequest・Resource）。
2. 小さなコミット＆段階的PR（ただし単一ブランチでも可）。差分を最小に。
3. Secrets禁止。DB/RedisはDockerサービス（Sail or Actions services）で起動。
4. OpenAPIに準拠し、実装とスキーマの乖離を出さない。

## 完了条件
- `backend-laravel/` で `sail up -d` → `php artisan test` がグリーン。
- Next から Cookie 認証で API を呼べる（Sanctum SPA）。
- `.github/workflows/backend-laravel.yml` がCI成功。
