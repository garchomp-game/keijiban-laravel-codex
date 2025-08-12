# Conventions

## JSON Envelope
```json
{
  "data": {...},      // 成功時の本体
  "meta": {...},      // ページング等
  "error": null       // 失敗時は { code, message, details }
}
```

## エラーコード（例）
- `AUTH-001` 認証失敗
- `THREAD-404` スレッド未発見
- `VALIDATION-422` バリデーション失敗

## 命名
- テーブル: `threads`, `posts`, `reactions`
- 外部キー: `thread_id`, `post_id`, `user_id`
- タイムスタンプ: `created_at`, `updated_at`, `deleted_at`
