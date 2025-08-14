export async function api(path: string, init: RequestInit = {}) {
  const res = await fetch(`${process.env.NEXT_PUBLIC_API_BASE_URL}${path}`, {
    credentials: 'include',
    headers: { 'Content-Type': 'application/json', ...(init.headers || {}) },
    ...init,
  });
  if (!res.ok) {
    const message = await res.text();
    throw new Error(message || 'API error');
  }
  return res.json();
}

export async function login(email: string, password: string) {
  await fetch(process.env.NEXT_PUBLIC_CSRF_ENDPOINT!, { credentials: 'include' });
  return api('/auth/login', {
    method: 'POST',
    body: JSON.stringify({ email, password }),
  });
}

export async function logout() {
  return api('/auth/logout', { method: 'POST' });
}
