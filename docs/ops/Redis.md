# Redis 運用メモ

## 切り替え方

小規模環境では `.env` で以下を設定し、Redis を使わずに動作できます。

```env
CACHE_STORE=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
```

Redis を有効化する場合は、次の値に変更します。

```env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## キャッシュとキューのポリシー

- キャッシュ用途は `LFU/LRU` などの eviction policy を設定し、適宜期限切れを許容します。
- キュー用途は `noeviction` を推奨し、ジョブ消失を防ぎます。将来は別インスタンスに分離することも検討します。

## 本番での差し替え

本番環境では `REDIS_HOST` などを AWS ElastiCache 等のエンドポイントに差し替えるだけで利用できます。
