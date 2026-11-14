# Point-of-Sale (POS) Eloquent — Resource Controllers & Auth

**Course:** IT0049 (Web System Technologies)  
**Assessment:** Technical Formative Assessment 8 (TFA8) — Locking It Down  
**Framework:** Laravel 13.x (PHP 8.3+)  
**Hosted Application:** [https://tfa8.infinityfreeapp.com](https://tfa8.infinityfreeapp.com)  
**GitHub Repository:** [https://github.com/aniciete/tfa8](https://github.com/aniciete/tfa8)

---

## 1. Project Overview

This project extends the **Laravel POS Eloquent** application (TFA5–TFA7) by refactoring customer and user CRUD endpoints into full RESTful **`Route::resource()`** registrations backed by conventional resource controllers (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`), installing **Laravel Breeze** (Blade stack), and protecting all account management routes behind Laravel's **`auth` middleware**.

### Route & Access Control Matrix

| URI Pattern | HTTP Method(s) | Controller Action | Access Control | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| `/` | `GET` | `PageController@home` | Public | Landing page & status banner |
| `/about` | `GET` | `PageController@about` | Public | Resource routing & auth middleware guide |
| `/login`, `/register` | `GET`, `POST` | Breeze Auth Controllers | `guest` | Operator sign-in & self-registration |
| `/forgot-password`, `/reset-password` | `GET`, `POST` | Breeze Password Reset | `guest` | Password recovery flow |
| `/dashboard` | `GET` | `PageController@dashboard` | `auth` | Authenticated POS KPI summary |
| `/customers` (`Route::resource`) | `GET/POST/PUT/PATCH/DELETE` | `CustomerController` (7 actions) | `auth` | Full CRUD + record view for customers |
| `/users` (`Route::resource`) | `GET/POST/PUT/PATCH/DELETE` | `UserController` (7 actions) | `auth` | Full CRUD + avatar upload & record view |
| `/profile` | `GET/PATCH/DELETE` | `ProfileController` | `auth` | Personal Breeze profile & password settings |
| `/avatars/{filename}` | `GET` | Closure (`Storage::disk('public')`) | `auth` | Authenticated avatar serving fallback |

---

## 2. Authentication & Resource Controller Architecture

1. **Laravel Breeze Integration:**
   - Installed via `laravel/breeze` (`breeze:install blade`), keeping Laravel's native password hashing (`bcrypt`), session regeneration, CSRF protection, and rate-limited login security intact.
   - `0001_01_01_000005_add_breeze_auth_columns_to_users_table.php` adds `email` (`unique`, nullable for legacy POS staff entries), `email_verified_at`, `password`, and `remember_token` to the `users` table.
   - `App\Models\User` preserves `username`, `full_name`, `role`, and `avatar` while mapping Breeze's conventional `name` attribute cleanly to `full_name` and generating unique POS usernames on `/register`.

2. **Full Resource Controllers (`CustomerController` & `UserController`):**
   - Registered cleanly via single `Route::resource('customers', CustomerController::class)` and `Route::resource('users', UserController::class)` declarations inside `Route::middleware('auth')->group(...)`.
   - Includes `show()` detail views (`customers.show`, `users.show`) and `destroy()` delete endpoints backed by an accessible native `<dialog>` confirmation modal.
   - `UserController::destroy()` prevents deleting the currently signed-in account from the staff directory and removes stored avatar files after deletion succeeds.

---

## 3. Local Development Setup

### Prerequisites
- PHP **8.3+** with `pdo_sqlite`, `pdo_mysql`, `mbstring`, `openssl`, `gd`, and `zip`
- Composer **2.x** & Node.js / npm

### Setup Commands
```bash
git clone https://github.com/aniciete/tfa8.git
cd tfa8
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan storage:link
```

### Start Local Server
```bash
php artisan serve
```
Open **[http://localhost:8000](http://localhost:8000)**.

### Seeded Staff Login Credentials
- **Email:** `admin.reyes@example.com`
- **Password:** `password`
*(You can also register a new account at `/register`.)*

---

## 4. Automated Test Suite

Run PHPUnit feature & unit tests:
```bash
php artisan test
```
Coverage includes:
- Laravel Breeze authentication suite (`AuthenticationTest`, `RegistrationTest`, `PasswordConfirmationTest`, `PasswordResetTest`, `PasswordUpdateTest`, `EmailVerificationTest`, `ProfileTest`)
- Middleware redirect verification for all unauthenticated guests across `/dashboard`, `/customers/*`, `/users/*`, `/profile`, and `/avatars/*` (`PosAuthAndResourceTest`)
- Resource controller CRUD (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`), validation & `old()` retention, self-delete guard, and Intervention Image 256&times;256 avatar upload lifecycle (`PosEloquentTest`, `PosFormsAndUploadTest`, `PosAuthAndResourceTest`)

---

## 5. Database Export & Shared Hosting Deployment

- Schema migrations & seeders: `database/migrations/` & `database/seeders/`
- Standalone MySQL DDL/DML export: [`database/schema_export.sql`](database/schema_export.sql)
- Production deployment: copy `env.production.example` to `.env.production` (gitignored), configure `.deploy.env` (gitignored), run `./build-infinityfree-zip.sh` and `./deploy-infinityfree.sh`, then visit `https://tfa8.infinityfreeapp.com/extract.php`.
- Live application: **[https://tfa8.infinityfreeapp.com](https://tfa8.infinityfreeapp.com)**
