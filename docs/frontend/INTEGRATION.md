# Next.js Integration (Sanctum SPA)

## 概要
Sanctum SPA モードでは、フロントは Cookie ベースで認証する。手順は「CSRF Cookie 取得 → ログイン → 以降のAPIは with-credentials」。

## 最小コード例（App Router）
```ts
// lib/api.ts
const API = process.env.NEXT_PUBLIC_API_BASE_URL!;
const CSRF = process.env.NEXT_PUBLIC_CSRF_ENDPOINT!;

export async function csrf() {
  await fetch(CSRF, { credentials: 'include' });
}

export async function login(email: string, password: string) {
  await csrf();
  const res = await fetch(`${API}/auth/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    credentials: 'include',
    body: JSON.stringify({ email, password }),
  });
  if (!res.ok) throw new Error('login failed');
}

export async function me() {
  const res = await fetch(`${API}/user`, { credentials: 'include' });
  if (!res.ok) throw new Error('unauthorized');
  return res.json();
}
```
