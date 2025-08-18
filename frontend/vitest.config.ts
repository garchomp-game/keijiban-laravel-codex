import { defineConfig } from 'vitest/config';
import { fileURLToPath } from 'node:url';
import { dirname } from 'node:path';

const dir = dirname(fileURLToPath(import.meta.url));

export default defineConfig({
  test: {
    environment: 'jsdom',
    include: ['src/**/*.test.tsx'],
    globals: true,
  },
  resolve: {
    alias: {
      '@': dir,
    },
  },
  esbuild: {
    jsx: 'automatic',
    jsxImportSource: 'react',
  },
});
