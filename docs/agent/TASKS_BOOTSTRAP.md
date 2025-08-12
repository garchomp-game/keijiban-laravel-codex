# TASKS: Bootstrap in This Repo

> すべて**このリポ**に作成。ファイル/ディレクトリが無ければ新規作成。

## Phase 0: スキャフォールド
1. `backend-laravel/` を作成  
   - `composer create-project laravel/laravel backend-laravel`
   - `cd backend-laravel && php artisan sail:install`（pgsql, redis, mailpit）
   - `.env` は `docs/agent/env/laravel.env.example` をベースに調整
   - `docker compose up -d` または `./vendor/bin/sail up -d`
2. `frontend/` を作成  
   - `npx create-next-app@latest frontend --ts --eslint`
3. CI 追加  
   - `docs/agent/CI_GUIDE.md` のYAMLを `.github/workflows/backend-laravel.yml` として配置
4. ドキュメント同梱  
   - `docs/agent/api/openapi.yaml` を実装参照に使う

## Phase 1: 認証（Sanctum SPA）
- `composer require laravel/sanctum`、publish & migrate
- CORS/サンクトム `stateful` を Next に合わせて設定
- ルート（`routes/api.php`）: `/sanctum/csrf-cookie`, `/auth/register`, `/auth/login`, `/auth/logout`, `/user`
- FormRequest と Resource を用いてJSON整形（`docs/agent/CONVENTIONS.md` 準拠）
- Pestで統合テスト（登録/ログイン/ログアウト/ユーザー取得）

## Phase 2: 掲示板ドメイン
- モデル/マイグレーション/コントローラ（`Thread`, `Post`, `Reaction`）
- ポリシー（作成者のみ更新/削除）
- シーディング（ダミーデータ）
- API ルート：`/threads` (CRUD), `/threads/{id}/posts` (list/create)
- ResourceとEager LoadingでN+1回避

## Phase 3: Next連携
- `docs/frontend/INTEGRATION.md` の通り、Cookie認証(`credentials: 'include'`)
- ログインフォームとスレッド一覧/詳細/投稿最小UI
- `.env.local` は `docs/agent/env/next.env.example` をベースに

## Phase 4: 仕上げ
- Pint/Larastan導入（任意）・CIに静的解析/整形ジョブ追加
- README更新（起動・テスト手順）
