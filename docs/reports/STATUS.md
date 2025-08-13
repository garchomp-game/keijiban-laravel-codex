# Implementation Status Report

## Summary
- Endpoint Parity: ⚠️
- Security & Auth: ⚠️
- Policies: ⚠️
- Caching: ⚠️
- Queue/Horizon: ✅
- CI & QA: ✅
- Static Analysis: ✅
- OpenAPI Lint: ⛳ (Could not run: npx failed to fetch packages)

## Endpoint Parity
Could not generate `route:list` (vendor autoload missing). Routes inspected statically from `routes/api.php`.

| Method | Path | OpenAPI | Routes | Authz (spec / code) | Tests |
| --- | --- | --- | --- | --- | --- |
| POST | /auth/register | ✅ | ✅ | none / none | AuthTest |
| POST | /auth/login | ✅ | ✅ | none / none | AuthTest |
| POST | /auth/logout | ✅ | ✅ | cookieAuth / auth:sanctum | AuthTest |
| GET | /user | ✅ | ✅ | cookieAuth / auth:sanctum | AuthTest |
| GET | /threads | ✅ | ✅ | cookieAuth / none | Thread* | 
| POST | /threads | ✅ | ✅ | cookieAuth / auth:sanctum | ThreadTest |
| GET | /threads/{thread} | ✅ | ✅ | cookieAuth / none | ThreadTest |
| PATCH | /threads/{thread} | ✅ | ✅ | cookieAuth / auth:sanctum | ThreadTest |
| DELETE | /threads/{thread} | ✅ | ✅ | cookieAuth / auth:sanctum | ThreadTest |
| GET | /threads/{thread}/posts | ✅ | ✅ | cookieAuth / none | PostTest |
| POST | /threads/{thread}/posts | ✅ | ✅ | cookieAuth / auth:sanctum | PostTest |

## Security & Auth
- `auth:sanctum` applied to thread/post creation and user/logout routes. Listing and show endpoints are public.
- OpenAPI marks most endpoints as requiring `cookieAuth`; public ones should drop security or add explicit `security: []`.
- CSRF handled by `SESSION_COOKIE` and Sanctum; CORS allows `FRONTEND_URL` and `supports_credentials`.
- No login rate limiting (`ThrottleRequests`) detected.

## Policies
- `ThreadPolicy` exists for `update`/`delete`; auto-discovered via naming conventions.
- No `PostPolicy`; PostController lacks authorization checks.
- Authorization tests (401/403) are absent.

## Caching
- `ThreadController@index` caches paginated threads with TTL from `config('cache.ttl.threads_index')` (default 60s).
- `CacheKeys::bumpThreadsVersion()` used on thread create/update/delete.
- No caching for posts; cache busting limited to threads.

## Queue/Horizon
- `laravel/horizon` installed with config `allow_dashboard` gating via `Gate::define('viewHorizon')`.
- `TouchCacheJob` implements `ShouldQueue`.
- CI uses synchronous queues; Horizon ops documented in `docs/ops/Horizon.md`.

## CI & QA
- Workflows: `backend-laravel.yml` (test, test-redis, quality) and `status-report.yml`.
- Test jobs provision Postgres (and Redis for `test-redis`), set CACHE/SESSION/QUEUE envs.
- Quality job runs Pint, Larastan (PHPStan), Composer audit, OpenAPI lint, and coverage tests.
- Composer scripts: `lint`, `analyse`, `test:coverage`, `audit:packages`.

## Static Analysis
- `phpstan-baseline.neon` contains 1 ignored error (`method.alreadyNarrowedType` in tests).
- Pint and Larastan integrated via composer scripts.

## OpenAPI Lint
Couldn’t run: `npx @redocly/cli@latest lint ../docs/agent/api/openapi.yaml` (npx failed to fetch packages).
Manual checks:
- Root `security` uses `cookieAuth`; login/register operations override with empty security.
- Path params `{id}` defined for thread and posts routes.
- All operations have `operationId`.
- 4xx responses included, but components/schemas for Thread/Post/User/Error/Paginated are missing.

## Next Actions
1. **Align OpenAPI security with public routes**
   - Update OpenAPI paths (`/threads`, `/threads/{id}`, `/threads/{id}/posts`) to `security: []` or document as public.
   - Acceptance: Redocly lint passes; spec matches route middleware.
2. **Introduce PostPolicy and enforce authorization**
   - Add `PostPolicy` with `update`/`delete`; register in `AuthServiceProvider` and call `$this->authorize` in PostController where needed.
   - Acceptance: Feature tests cover 403 for non-owners.
3. **Add login rate limiting**
   - Apply `ThrottleRequests` middleware to `/auth/login` route with sensible limits.
   - Acceptance: Rate limiting documented and test verifies 429 after exceeding limit.
4. **Expand OpenAPI components**
   - Define schemas for Thread, Post, User, Error, Paginated and reference them in responses.
   - Acceptance: Redocly lint passes with no schema-related warnings.
5. **Generate route list in status reports**
   - Ensure vendor dependencies or artifact installation so `php artisan route:list` succeeds in CI and local status tooling.
   - Acceptance: `docs/reports/routes.txt` contains actual route table.
