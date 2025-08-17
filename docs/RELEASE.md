# Release Process

## Commands

Run these commands to verify the application before releasing.

### Backend (`backend-laravel`)

1. Start containers: `sail up -d`
2. Run backend tests: `php artisan test`
3. Run JavaScript tests: `npm test`
4. Build assets: `npm run build`

### Frontend (`frontend`)

1. Run tests: `npm test`
2. Build assets: `npm run build`

## Pre-release Checklist

- [ ] README.md is up to date
- [ ] Documentation under `docs/` reflects current functionality
- [ ] Sample environment file (e.g., `backend-laravel/.env.example`) is current
