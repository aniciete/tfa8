# Point-of-Sale (POS) Eloquent — Forms, Validation & File Uploads

**Course:** IT0049 (Web System Technologies)  
**Assessment:** Technical Formative Assessment 7 (TFA7) — Making It Editable in Laravel  
**Framework:** Laravel 13.x (PHP 8.3+)  
**Hosted Application:** [http://tfa7.freehosting.dev](http://tfa7.freehosting.dev)  
**GitHub Repository:** [https://github.com/aniciete/tfa7](https://github.com/aniciete/tfa7)

---

## 1. Project Overview

This project extends the **Laravel POS Eloquent** application from TFA6 with validated create and edit forms for both **customers** and **staff users**, complete with `old()` input retention, inline field and summary validation feedback, and user avatar uploads prepared for display using **Intervention Image (`intervention/image-laravel`)** on Laravel's `Storage::disk('public')`.

### Required Pages and Route Table

| URL Route | Method | Route Name | Controller Action | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| `/` | `GET` | `home` | `PageController@home` | Landing overview with directory & creation links |
| `/about` | `GET` | `about` | `PageController@about` | Request validation & `Storage` upload flow guide |
| `/customers` | `GET` | `customers.index` | `CustomerController@index` | Customer directory with `+ New Customer` and row `Edit` links |
| `/customers/create` | `GET` | `customers.create` | `CustomerController@create` | New customer creation form |
| `/customers` | `POST` | `customers.store` | `CustomerController@store` | Validate (`SaveCustomerRequest`) and insert customer |
| `/customers/{customer}/edit` | `GET` | `customers.edit` | `CustomerController@edit` | Edit form pre-filled with existing customer data |
| `/customers/{customer}` | `PUT` | `customers.update` | `CustomerController@update` | Validate and update customer (`Rule::unique()->ignore()`) |
| `/users` | `GET` | `users.index` | `UserController@index` | Staff user directory displaying 40&times;40 avatars or placeholder |
| `/users/create` | `GET` | `users.create` | `UserController@create` | New user form with optional avatar upload |
| `/users` | `POST` | `users.store` | `UserController@store` | Validate (`SaveUserRequest`), prepare avatar, and insert user |
| `/users/{user}/edit` | `GET` | `users.edit` | `UserController@edit` | Edit user form pre-filled with current details & avatar preview |
| `/users/{user}` | `PUT` | `users.update` | `UserController@update` | Validate, crop/store new avatar if uploaded, and clean up old image |

---

## 2. Validation & Image Handling Architecture

1. **Form Requests (`app/Http/Requests/`):**
   - `SaveCustomerRequest`: trims inputs before validation; requires `full_name` (`max:100`), requires a valid unique `email` (`Rule::unique('customers', 'email')->ignore($customer?->id)`), and accepts optional `phone` (`max:20`).
   - `SaveUserRequest`: trims inputs and defaults omitted roles to `'Staff Member'`; requires unique `username` (`regex:/^[a-zA-Z0-9._-]+$/`, `Rule::unique('users', 'username')->ignore($user?->id)`), requires `full_name` (`max:100`), accepts operational `role`, and validates `avatar` (`nullable|file|image|max:2048` — 2 MB max, rejects SVGs & non-image files).
   - Validation failures automatically redirect back with `old()` input and `$errors` displayed in both an accessible `#form-error-summary` (`role="alert"`) and field-level error notes (`aria-invalid="true"`).

2. **Display-Ready Avatar Storage (`app/Services/AvatarService.php` & `database/migrations/0001_01_01_000004_add_avatar_to_users_table.php`):**
   - Migration adds a nullable `avatar` (`varchar(255)`) column to `users`.
   - `AvatarService::prepareAndStore()` uses `Intervention\Image\Laravel\Facades\Image` (`GD` driver) to decode uploaded files, center-crop/resize to **256&times;256**, encode cleanly by format (`PNG`, `WEBP`, or `JPEG`), store under `avatars/<uuid>.<ext>` via `Storage::disk('public')`, and save **only the relative path** in `users.avatar`.
   - Old replaced avatars are deleted only after database persistence succeeds; users without an uploaded image gracefully fall back to `public/assets/images/avatar-placeholder.svg`.

---

## 3. Local Development Setup

### Prerequisites
- PHP **8.3+** with `pdo_sqlite`, `pdo_mysql`, `mbstring`, `openssl`, `gd`, and `zip`
- Composer **2.x** & Node.js / npm

### Setup Commands
```bash
git clone https://github.com/aniciete/tfa7.git
cd tfa7
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

---

## 4. Automated Test Suite

Run PHPUnit feature & unit tests:
```bash
php artisan test
```
Tests verify:
- Landing, About, Customers, and Users routes (`PosEloquentTest`)
- Customer create/edit validation, `old()` input retention, duplicate email rejection, and self-email update (`PosFormsAndUploadTest`)
- User create/edit validation and duplicate/dotted username rules (`PosFormsAndUploadTest`)
- Avatar upload image validation (&le; 2 MB, image-only), 256&times;256 resizing via Intervention Image, `Storage::disk('public')` storage & cleanup, and fallback placeholder rendering (`PosFormsAndUploadTest`)

---

## 5. Database Export & Shared Hosting Deployment

- Schema migrations & seeders: `database/migrations/` & `database/seeders/`
- Standalone MySQL DDL/DML export: [`database/schema_export.sql`](database/schema_export.sql)
- Packaging & FTP deployment: copy `env.production.example` to `.env.production` (gitignored), set `FTP_USER`/`FTP_PASS` in `.deploy.env` (gitignored), and run `./deploy-infinityfree.sh`.
- Live application: **[http://tfa7.freehosting.dev](http://tfa7.freehosting.dev)**
