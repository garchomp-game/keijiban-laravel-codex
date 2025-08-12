# Agent Overview (Laravel + Next)

## 原則
1. 設定より規約：Laravel 標準（Sanctum, Eloquent, Resource Controller, Request Validation）を優先。
2. 変更は最小限コミット・小粒 PR。実装→テスト→CI 修正の順で段階化。
3. 破壊的変更は TASKS_LARAVEL_MIGRATION.md の順序に従うこと。
4. 秘密情報は使わない（fork PR 安全）。CI は Docker サービスで完結。

## 作業モード
- まず backend-laravel を新設し、Sail で pg/redis を有効化。
- 認証は Sanctum SPA。Next 側は `credentials: 'include'` で Cookie を送信。
- API は OpenAPI に準拠。E2E/統合テストは Pest で書く。

## コード規約（抜粋）
- 名前: Eloquent モデルは単数（User, Thread, Post, Reaction）。テーブルは複数形。
- コントローラ: `php artisan make:controller ThreadController --api --model=Thread`
- バリデーション: `FormRequest` を必須化。
- レスポンス: JSON envelope `{ data, meta, error }` を標準化（CONVENTIONS.md）。
- 例外: `App\Exceptions\Handler` で API 例外を JSON に統一。
