# TASKS: Laravel Migration

## 0. 下準備
- ディレクトリ `backend-laravel/` を作成し、Laravel 11 を初期化：
  - `composer create-project laravel/laravel backend-laravel`
  - `cd backend-laravel && php artisan sail:install` → `pgsql, redis, mailpit` を選択
  - `.env` を編集（docs/agent/env/laravel.env.example を参照）

## 1. 認証基盤（Sanctum）
- `composer require laravel/sanctum`
- `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`
- `php artisan migrate`
- `config/sanctum.php`: SPA ドメイン、`stateful` を設定
- `config/cors.php`: Next のオリジン、`supports_credentials`=true
- ルート：
  - `routes/api.php`: `/sanctum/csrf-cookie`, `/login`, `/logout`, `/user`, `/register`
- コントローラ/Request 作成（AuthController, RegisterRequest, LoginRequest）

## 2. 掲示板ドメイン
- モデル/マイグレーション生成：
  - `php artisan make:model Thread -mcr`
  - `php artisan make:model Post -mcr`
  - `php artisan make:model Reaction -m`
- 関連：
  - User hasMany Thread/Post、Thread hasMany Post、Post hasMany Reaction
- ポリシー（権限）：
  - `php artisan make:policy ThreadPolicy --model=Thread`（作成者のみ編集/削除）
- シーディング：
  - `php artisan make:seeder DemoSeeder`（ユーザーとダミー投稿数十件）

## 3. API
- ルート（`api.php`）: `Route::apiResource('threads', ThreadController::class)` 等
- リクエスト/レスポンスは OpenAPI（docs/agent/api/openapi.yaml）に合わせる
- 変換: API Resource (`php artisan make:resource ThreadResource`)
- エラーフォーマット統一（CONVENTIONS.md）

## 4. テスト & CI
- Pest 導入（Laravel 11 既定）。並列実行を有効化
- 認証フロー、スレッド作成、投稿、リアクションの統合テスト
- GitHub Actions を追加（docs/agent/CI_GUIDE.md の yml を使用）

## 5. Next.js 連携
- `NEXT_PUBLIC_API_BASE_URL`, `NEXT_PUBLIC_CSRF_ENDPOINT` を設定
- 認証：`/sanctum/csrf-cookie` → `/login` の順で Cookie を取得し、`credentials: 'include'`
- 例: docs/frontend/INTEGRATION.md
