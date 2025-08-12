# Horizon 運用メモ

## 起動

ローカルやステージングで Horizon を試すには以下を実行します。

```bash
php artisan horizon
```

Laravel Sail を使う場合は:

```bash
./vendor/bin/sail artisan horizon
```

`HORIZON_ALLOW_DASHBOARD=true` の環境でのみダッシュボードにアクセスできます。

## 主要設定

- `config/horizon.php` でバランサーは `balance` => `auto` が既定です。
- スーパーバイザの設定は `supervisors` セクションで確認できます。

## 本番運用の注意

- 本番では Supervisor や systemd で `php artisan horizon` を常駐させます。
- キュー用 Redis は `noeviction` を推奨し、キャッシュ用途とは分離してください。
