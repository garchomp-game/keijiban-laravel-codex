# Developer Guide

## Local Setup
- Copy `.env.example` to `.env` in `backend-laravel/` and run `composer install`.
- Start the backend with Laravel Sail: `./vendor/bin/sail up -d`.
- For the frontend, install dependencies with `npm install` and run `npm run dev`.

## Testing
- Run backend tests with `php artisan test`.
- Lint the frontend with `npm run lint`.

## FAQ
- **Tests fail because Redis is unavailable.**
  Set `CACHE_STORE=array`, `SESSION_DRIVER=array`, and `QUEUE_CONNECTION=sync` in `.env` to disable Redis.
- **How do I generate an application key?**
  After copying `.env.example`, run `php artisan key:generate`.
