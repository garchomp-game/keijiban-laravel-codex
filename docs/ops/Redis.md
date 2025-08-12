# Redis 運用メモ

## 切り替え方

ローカル開発 (Laravel Sail) では Redis コンテナが起動し、`.env.example` で
`CACHE_STORE=redis` `SESSION_DRIVER=redis` が既定 ON です。キューは現状 `sync`
のままで、将来 `QUEUE_CONNECTION=redis` に切り替えるだけで利用できます。

CI は `array/sync/array` で動かす高速ジョブ `test` と、Redis サービスを起動して
`--group=redis` だけ実行する `test-redis` の2ジョブ構成です。
Redis を使わない環境では、以下を設定するとネイティブ PHP のみで高速に動作します。

```env
CACHE_STORE=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
```

Redis を本番や別環境で有効化する場合は、次の値に変更します。

```env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

CI で Redis を使う際は、ワークフローに `services: redis` を追加し、上記の値に切り替えるだけで動作します。

## キャッシュとキューのポリシー

- キャッシュ用途は `LFU/LRU` などの eviction policy を設定し、適宜期限切れを許容します。
- キュー用途は `noeviction` を推奨し、ジョブ消失を防ぎます。将来は別インスタンスに分離することも検討します。

### スレッド一覧キャッシュ

`/api/threads` は `threads:index:v{n}` 形式のキーでページごとにキャッシュされます。
スレッドの作成・更新・削除時に `threads:version` をインクリメントして一括無効化する安全設計です。
TTL は `CACHE_TTL_THREADS` (秒) で調整できます。

## 本番での差し替え

本番環境では `REDIS_HOST` などを AWS ElastiCache 等のエンドポイントに差し替えるだけで利用できます。
