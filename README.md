# KAS Food Delivery

Laravel-based food delivery system (customer + driver + admin) with **email OTP verification** during registration.

## Requirements

- **PHP**: 8.2+
- **Composer**
- **Node.js + npm**
- **MySQL** (recommended for this project) or SQLite (default in `.env.example`)
- **Mail provider** for OTP emails (this README uses **Gmail SMTP App Password**)

## Quick start (local setup)

### 1) Install dependencies

```bash
composer install
npm install
```

### 2) Create your `.env`

```bash
copy .env.example .env
php artisan key:generate
```

### 3) Configure database (MySQL)

Edit `.env` and set these:

- **DB_CONNECTION**: `mysql`
- **DB_HOST**: `127.0.0.1`
- **DB_PORT**: `3306`
- **DB_DATABASE**: your database name (example: `food_delivery`)
- **DB_USERNAME** / **DB_PASSWORD**

Create the database in phpMyAdmin (or MySQL CLI), then run:

```bash
php artisan migrate
```

### 4) Configure Mail (required for OTP)

OTP emails are sent via Laravel Mail. Configure SMTP in `.env` (example: Gmail):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=yourgmail@gmail.com
MAIL_PASSWORD=your_google_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=yourgmail@gmail.com
MAIL_FROM_NAME="KAS Delivery"
```

Then clear cached config:

```bash
php artisan config:clear
```

### 5) Run the app

```bash
php artisan serve
npm run dev
```

Open the app at `http://127.0.0.1:8000`.

## OTP (One-Time Password) verification

### What OTP is used for in this project

OTP is used for **email verification during registration**. After a user registers, they must enter a **6-digit code** sent to their email to activate email verification.

### Where OTP logic is implemented

- **Generate + send OTP (registration)**: `app/Http/Controllers/AuthController.php`
- **Verify OTP + Resend OTP**: `app/Http/Controllers/OTPVerificationController.php`
- **OTP email template**: `resources/views/emails/otp.blade.php`
- **OTP input page**: `resources/views/auth/verify-otp.blade.php`
- **DB fields**: `users.otp` and `users.otp_expires_at` (see migrations)
- **Routes**: `routes/web.php` (`otp.form`, `otp.verify`, `otp.resend`)

### Step-by-step: How OTP works (flow)

#### A) Registration generates an OTP

1. User submits the registration form.
2. The system creates a **new user** with `email_verified_at = null`.
3. The system generates a **6-digit OTP** (e.g. `123456`).
4. The OTP is stored in the database:
   - `users.otp` = the 6-digit code
   - `users.otp_expires_at` = now + **10 minutes**
5. The OTP is emailed to the user using `Mail::send(...)`.
6. The user is redirected to the OTP screen: `/verify-otp/{email}`.

#### B) Verifying the OTP

1. User enters the 6-digit code on the OTP page.
2. The server checks:
   - The email exists in `users`
   - The email is **not already verified**
   - The OTP matches `users.otp`
   - The OTP is **not expired** (`now` is before `otp_expires_at`)
3. If valid, the server:
   - sets `email_verified_at = now()`
   - clears `otp` and `otp_expires_at`
4. User is redirected to login.

#### C) Resending the OTP

When the user clicks **Resend OTP**:

- If there is **no existing OTP**, or it is **already expired**, the system generates a **new 6-digit OTP**.
- If the OTP is still valid, the system **reuses the same OTP** and **extends the expiry** by another **10 minutes**.
- The OTP is emailed again.

### OTP validity rules (current behavior)

- **OTP length**: 6 digits
- **Expiry time**: 10 minutes
- **Verification success**: marks email as verified and clears OTP fields
- **Resend behavior**:
  - valid OTP → keep OTP, extend expiry
  - expired/missing OTP → generate new OTP

## Google (Gmail) App Password setup (step-by-step)

Gmail SMTP requires an **App Password** (not your normal Gmail password) if you have **2-Step Verification** enabled.

### 1) Enable 2-Step Verification

1. Go to your Google Account.
2. Open **Security**.
3. Turn on **2-Step Verification**.

### 2) Create an App Password

1. In Google Account **Security**, find **App passwords**.
2. Choose an app name (example: `KAS Delivery SMTP`).
3. Generate the password.
4. Copy the **16-character** app password (it may display with spaces).

### 3) Put the App Password into `.env`

In `.env`:

- Set **MAIL_USERNAME** to your Gmail address (example: `yourgmail@gmail.com`)
- Set **MAIL_PASSWORD** to the **App Password** you generated (paste it without extra quotes)
- Keep these values:
  - `MAIL_HOST=smtp.gmail.com`
  - `MAIL_PORT=587`
  - `MAIL_ENCRYPTION=tls`

After editing `.env`, run:

```bash
php artisan config:clear
```

### 4) Test OTP email (manual check)

1. Register a new user account in the app.
2. Confirm the OTP email arrives in Inbox (or Spam).
3. Enter the OTP on the verification page.

## Troubleshooting

### OTP email not sending

- **Check mail config is SMTP**: `MAIL_MAILER=smtp`
- **Wrong credentials**: Gmail requires an **App Password**, not your normal password
- **Config cached**: run `php artisan config:clear`
- **Blocked by provider**: try another Gmail account or a dedicated email provider

### OTP expires too quickly

- OTP expiry is set to **10 minutes** in:
  - `AuthController::register()` (initial OTP)
  - `OTPVerificationController::resend()` (resend/extend)

### Important security note

- **Never commit** real credentials (SMTP username/app password) into Git.
- Keep `.env` private and use `.env.example` for safe defaults.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
"# KAS-FOOD_DELIVERY" 
