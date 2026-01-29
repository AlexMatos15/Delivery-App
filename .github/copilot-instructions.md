# Copilot instructions for this repo

## Project overview
- Laravel 12 (PHP ^8.2) app using the default Laravel structure; primary entrypoint is [public/index.php](public/index.php).
- Web requests flow through route files (start with [routes/web.php](routes/web.php)) to controllers in [app/Http/Controllers](app/Http/Controllers), then render Blade views in [resources/views](resources/views).
- Eloquent models live in [app/Models](app/Models), with migrations in [database/migrations](database/migrations) and factories/seeders in [database/factories](database/factories) and [database/seeders](database/seeders).

## Authentication & Authorization
- **Framework**: Laravel Breeze provides login/logout, registration, password reset, and email verification.
- **User Types**: Users have a `type` enum (client, admin, shop) and `is_admin` boolean flag in [app/Models/User.php](app/Models/User.php).
- **User Status**: Users have an `is_active` boolean flag; inactive users are logged out on request via [app/Http/Middleware/EnsureUserIsActive.php](app/Http/Middleware/EnsureUserIsActive.php).
- **Admin Middleware**: [app/Http/Middleware/EnsureAdmin.php](app/Http/Middleware/EnsureAdmin.php) restricts routes to admins; register as 'admin' alias in [bootstrap/app.php](bootstrap/app.php).
- **Role Helpers**: User model includes `isAdmin()`, `isShop()`, `isClient()`, `isActive()` methods for conditional logic.
- **Profile Routes**: `/profile` (edit), `/my-profile` (view), authenticated endpoints at [routes/web.php](routes/web.php).
- **Admin Routes**: `/admin/users` lists all users; admins can activate/deactivate or delete users (see [app/Http/Controllers/Admin/UserManagementController.php](app/Http/Controllers/Admin/UserManagementController.php)).
- **Email Verification**: Enabled by default via Breeze; users must verify email before accessing protected routes.
- **Change Password**: `/confirm-password` and `/password` endpoints managed by Breeze auth routes in [routes/auth.php](routes/auth.php).

## Front-end assets
- Vite is configured in [vite.config.js](vite.config.js) with the Laravel Vite plugin; the main entrypoints are [resources/css/app.css](resources/css/app.css) and [resources/js/app.js](resources/js/app.js).
- Tailwind v4 is used via @import 'tailwindcss' in [resources/css/app.css](resources/css/app.css), with content sources declared using @source for Blade and JS files.
- HTTP client defaults are configured in [resources/js/bootstrap.js](resources/js/bootstrap.js) using axios.
- Blade templates typically include assets with @vite (see [resources/views/welcome.blade.php](resources/views/welcome.blade.php)).

## Local workflows (from composer/npm scripts)
- Initial setup (composer + npm + migrate + build): `composer run setup`.
- Full dev stack (php server + queue listener + pail logs + Vite): `composer run dev` (uses concurrently).
- Tests: `composer run test` (clears config then runs artisan test).
- Database migration: `php artisan migrate` (runs pending migrations, required after schema changes).
- Front-end only: `npm run dev` or `npm run build` (see [package.json](package.json)).

## Conventions observed
- Controllers are minimal; routes in [routes/web.php](routes/web.php) directly return views for simple pages.
- Asset hot-reload relies on Vite's hot file in public and Blade @vite checks for build/manifest.json or hot.
- Vite is configured to ignore storage view cache changes to avoid rebuild loops (see [vite.config.js](vite.config.js)).
- User types are immutable via profile UI (shown as read-only); changed only programmatically or in admin console.

## Where to extend
- Add new HTTP endpoints in [routes/web.php](routes/web.php); use `middleware(['auth', 'verified'])` for authenticated routes or `middleware('admin')` for admin-only routes.
- Add controllers under [app/Http/Controllers](app/Http/Controllers) and pair with Blade views under [resources/views](resources/views).
- Add new models under [app/Models](app/Models) with corresponding migrations under [database/migrations](database/migrations).
- To add admin-only pages, use `Route::middleware('admin')->group(...)` pattern.
