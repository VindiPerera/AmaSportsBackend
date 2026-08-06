# AmaSports Backend

Laravel REST API for the AmaSports performance platform, built for coaches and students. This repo is the backend only — it has no knowledge of the mobile client beyond the JSON contract described below. See [`sports-app-mobile`](../AmaSportsMobile) for the Expo client.

## Tech stack

- **Framework:** Laravel 12
- **Database:** MySQL
- **Auth:** Laravel Sanctum (personal access tokens / Bearer auth — not cookie sessions)
- **PHP:** 8.3+

## Requirements

- PHP 8.3+ with `mbstring`, `pdo_mysql`, `openssl`, `fileinfo`, `bcmath` extensions enabled
- Composer 2.x
- MySQL 8.x (or MariaDB 10.x+)

## Getting started

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` — at minimum set your MySQL credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amasports
DB_USERNAME=root
DB_PASSWORD=
```

Create the database, then run migrations + seeders:

```bash
mysql -u root -e "CREATE DATABASE amasports CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed
```

Start the dev server:

```bash
php artisan serve
```

The API is now available at `http://127.0.0.1:8000/api`. Point the mobile app's `EXPO_PUBLIC_API_URL` at this (see the mobile repo's README for platform-specific host notes).

### Seeded demo accounts

All seeded accounts use the password `password`:

| Email | Role |
|---|---|
| `coach@amasports.app` | coach |
| `student@amasports.app` | student |
| `admin@amasports.app` | admin |

## Folder structure

```
app/
  Http/
    Controllers/Api/       AuthController, ProfileController — one controller per resource
    Requests/Auth/         Form Request validation classes (one per endpoint)
    Resources/             UserResource — API-facing shape of models
    Middleware/            EnsureUserHasRole (generic role gate for future modules)
  Models/                  User, OtpCode
  Notifications/           OtpCodeNotification (password-reset emails)
  Services/                OtpService (issue + verify password-reset codes)
  Traits/                  ApiResponse (shared success()/error() envelope helpers)

routes/
  api.php                  All /api/* routes — public auth routes + Sanctum-protected routes

database/
  migrations/              users, personal_access_tokens, otp_codes, role/avatar columns
  factories/                UserFactory with coach()/student()/admin() states
  seeders/                  DatabaseSeeder — demo coach/student/admin + 10 random students
```

New domains (teams, performance analytics, live scores, live streaming, notifications) each get their own `Controller` + route group in `routes/api.php`, without touching auth. Role-gated endpoints can use the existing `role` middleware, e.g. `->middleware('role:coach')`.

## API response structure

Every endpoint returns the same JSON envelope, produced by the `ApiResponse` trait:

**Success:**
```json
{ "success": true, "message": "Login successful.", "data": { "...": "..." } }
```

**Validation / business error:**
```json
{ "success": false, "message": "The provided credentials are incorrect.", "errors": { "email": ["..."] } }
```

**Unhandled exception / 401 / 404:** same shape, no `errors` key, handled globally in `bootstrap/app.php` so nothing on `/api/*` ever falls back to Laravel's default HTML error page.

## Authentication endpoints

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| POST | `/api/auth/register` | – | Create account, returns token + user immediately (no email verification step) |
| POST | `/api/auth/login` | – | Returns token + user |
| POST | `/api/auth/logout` | ✅ | Revokes the current access token |
| POST | `/api/auth/forgot-password` | – | Sends a 6-digit password-reset code by email |
| POST | `/api/auth/reset-password` | – | `{ email, token, password, password_confirmation }` — resets password, revokes all tokens |
| GET | `/api/user` | ✅ | Current user's profile |
| PATCH | `/api/user` | ✅ | Update `name` / `email` / `avatar_url` |
| PUT | `/api/user/password` | ✅ | Change password while authenticated |

Authenticated requests use `Authorization: Bearer <token>`.

## OTP codes

Password reset uses a one-time code, backed by the `otp_codes` table (`app/Models/OtpCode.php`) and `OtpService`:

- 6-digit numeric code, expires after 10 minutes (`OtpCode::EXPIRY_MINUTES`)
- Issuing a new code invalidates any previous unused code for that email
- Codes are single-use (`used_at` is stamped on successful verification)
- Delivered via `OtpCodeNotification` (queued mail) — with `MAIL_MAILER=log` (the default), codes land in `storage/logs/laravel.log` instead of a real inbox, which is convenient for local development

The `type` column on `otp_codes` currently only has one value (`password_reset`) but is kept so a future OTP-gated feature can reuse the same table.

## CORS

Configured in `config/cors.php`, driven by `CORS_ALLOWED_ORIGINS` in `.env` (comma-separated origins, or `*`). The mobile app itself is a native Bearer-token client and isn't subject to CORS, but this matters once a web/admin dashboard is added.

## Validation

Every write endpoint has a dedicated `FormRequest` under `app/Http/Requests/Auth/` — controllers stay thin and every validation rule lives in one place per endpoint.

## Running tests

```bash
php artisan test
```
