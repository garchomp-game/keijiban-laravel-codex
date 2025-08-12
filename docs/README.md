# Keijiban: Laravel + Next Migration Guide (for Agents)

本プロジェクトは、バックエンドを Laravel に刷新し、Next.js と統合したリアルタイム掲示板を構築する。
このドキュメントは **エージェント（VS Code Copilot / Sonnet / Codex）向け**の実装指針と仕事の分割を定義する。

**重要**: このリポは仕様のみ。エージェントはこのリポ直下に `backend-laravel/` と `frontend/` を新規作成する。

## 目的
- 既存 Node/Nest + Prisma で生じた CI/DB/ENV の複雑性を軽減し、Laravel の「規約優先」で再構築。
- 認証は Laravel Sanctum（SPA モード）を第一候補。Next 側は Cookie ベースで連携。
- DB は PostgreSQL、キャッシュ/セッション/キューに Redis を利用。

## 技術スタック（固定）
- Backend: Laravel 11 / PHP 8.3 / Sanctum / Eloquent / Pest
- DB: PostgreSQL 16、Cache/Queue: Redis 7
- Frontend: Next.js 14+ (App Router)
- Local: Laravel Sail（Docker Compose）
- CI: GitHub Actions（PHP 8.3 + Postgres + Redis）
- API仕様: OpenAPI 3.1（docs/agent/api/openapi.yaml）

## フォルダ構成（このリポ直下に作成）
- backend-laravel/ … Laravel 11 アプリ（Sail）
- frontend/ … Next.js アプリ（App Router）
- docs/
  - agent/
    - AGENT_OVERVIEW.md … エージェント向けのルール
    - TASKS_BOOTSTRAP.md … 具体的なタスク列
    - CONVENTIONS.md … 命名規約・レスポンス標準
    - DB_SCHEMA.md … ER/テーブル・Eloquent 関係
    - CI_GUIDE.md … CI のやり方（Actions）
    - env/laravel.env.example, env/next.env.example
    - api/openapi.yaml … API の契約

## 実装対象ドメイン
- Users, Threads, Posts, Reactions（いいね等）
- 認証: ログイン/ログアウト/サインアップ、セッション更新、CSRF

## 完了の定義（Definition of Done）
- `sail up -d` でバックエンドが起動し `php artisan test` グリーン
- Next から Cookie 認証で `/api/threads` 等の CRUD が動作
- GitHub Actions で backend ジョブが安定グリーン（Pest / Pint / Larastan）
- OpenAPI と実装が乖離しない（スキーマチェックOK）
