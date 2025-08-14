import { test, expect } from '@playwright/test';

const API_BASE = process.env.API_BASE_URL || 'http://127.0.0.1:8000/api';
const CSRF_ENDPOINT = process.env.CSRF_ENDPOINT || 'http://127.0.0.1:8000/sanctum/csrf-cookie';

async function xsrfToken(request: any) {
  const state = await request.storageState();
  const cookie = state.cookies.find((c: any) => c.name === 'XSRF-TOKEN');
  return cookie ? decodeURIComponent(cookie.value) : '';
}

test('login, create thread, post, react', async ({ request }) => {
  // get CSRF cookie
  await request.get(CSRF_ENDPOINT);
  let token = await xsrfToken(request);

  const email = `user${Date.now()}@example.com`;
  const password = 'password';

  // register
  let res = await request.post(`${API_BASE}/auth/register`, {
    data: { name: 'Playwright User', email, password, password_confirmation: password },
    headers: { 'X-XSRF-TOKEN': token },
  });
  expect(res.status()).toBe(201);

  // login
  res = await request.post(`${API_BASE}/auth/login`, {
    data: { email, password },
    headers: { 'X-XSRF-TOKEN': token },
  });
  expect(res.status()).toBe(204);

  // refresh token after login
  await request.get(CSRF_ENDPOINT);
  token = await xsrfToken(request);

  // create thread
  res = await request.post(`${API_BASE}/threads`, {
    data: { title: 'Playwright Thread' },
    headers: { 'X-XSRF-TOKEN': token },
  });
  expect(res.status()).toBe(201);
  const thread = await res.json();
  const threadId = thread.data.id;

  // create post
  res = await request.post(`${API_BASE}/threads/${threadId}/posts`, {
    data: { body: 'first post' },
    headers: { 'X-XSRF-TOKEN': token },
  });
  expect(res.status()).toBe(201);
  const post = await res.json();
  const postId = post.data.id;

  // react to post
  res = await request.post(`${API_BASE}/posts/${postId}/reactions`, {
    data: { type: 'like' },
    headers: { 'X-XSRF-TOKEN': token },
  });
  expect(res.status()).toBe(201);
});
