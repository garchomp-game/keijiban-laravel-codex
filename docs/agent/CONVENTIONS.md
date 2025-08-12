# Conventions (This Repo)

- JSON Envelope:
  {
    "data": {...},
    "meta": {...},
    "error": null
  }
- テーブル/外部キー/タイムスタンプ命名は一般的なLaravel/Eloquent規約に準拠
- エラーは { code, message, details } を推奨（コードは AUTH-001 / THREAD-404 等）
