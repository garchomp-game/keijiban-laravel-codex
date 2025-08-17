# Release Process

## Commands

Run the following commands to verify the application before releasing.

### Backend (`backend-laravel`)

```bash
sail up -d
php artisan test
npm test
npm run build
```

### Frontend (`frontend`)

```bash
npm test
npm run build
```

## Pre-release Checklist

- [ ] README.md is up to date
- [ ] Documentation under `docs/` reflects current functionality
- [ ] Sample environment file (e.g., `backend-laravel/.env.example`) is current
