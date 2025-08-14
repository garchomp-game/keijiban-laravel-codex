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

