import createClient from 'openapi-fetch';
import type { paths } from './api-types';

const baseUrl = process.env.NEXT_PUBLIC_API_BASE_URL!;
const csrfEndpoint = process.env.NEXT_PUBLIC_CSRF_ENDPOINT!;

export const api = createClient<paths>({
  baseUrl,
  fetch: async (input: RequestInfo, init: RequestInit = {}) => {
    const method = (init.method ?? 'GET').toUpperCase();
    if (method !== 'GET' && method !== 'HEAD') {
      await fetch(csrfEndpoint, { credentials: 'include' });
    }
    return fetch(input, {
      credentials: 'include',
      headers: { 'Content-Type': 'application/json', ...(init.headers || {}) },
      ...init,
    });
  },
});

// Legacy API functions for backward compatibility
export async function legacyApi(path: string, init: RequestInit = {}) {
  const res = await fetch(`${baseUrl}${path}`, {
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
  await fetch(csrfEndpoint, { credentials: 'include' });
  const { error } = await api.POST('/auth/login', {
    body: {
      email,
      password,
    },
  });
  if (error) {
    throw new Error(error.message || 'Login failed');
  }
}

export async function logout() {
  const { error } = await api.POST('/auth/logout', {});
  if (error) {
    throw new Error(error.message || 'Logout failed');
  }
}
