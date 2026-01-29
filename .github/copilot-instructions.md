# Copilot instructions for this repo

## Project overview
- Laravel 12 (PHP ^8.2) app using the default Laravel structure; primary entrypoint is [public/index.php](public/index.php).
- Web requests flow through route files (start with [routes/web.php](routes/web.php)) to controllers in [app/Http/Controllers](app/Http/Controllers), then render Blade views in [resources/views](resources/views).
- Eloquent models live in [app/Models](app/Models), with migrations in [database/migrations](database/migrations) and factories/seeders in [database/factories](database/factories) and [database/seeders](database/seeders).

## Front-end assets
- Vite is configured in [vite.config.js](vite.config.js) with the Laravel Vite plugin; the main entrypoints are [resources/css/app.css](resources/css/app.css) and [resources/js/app.js](resources/js/app.js).
- Tailwind v4 is used via @import 'tailwindcss' in [resources/css/app.css](resources/css/app.css), with content sources declared using @source for Blade and JS files.
- HTTP client defaults are configured in [resources/js/bootstrap.js](resources/js/bootstrap.js) using axios.
- Blade templates typically include assets with @vite (see [resources/views/welcome.blade.php](resources/views/welcome.blade.php)).

## Local workflows (from composer/npm scripts)
- Initial setup (composer + npm + migrate + build): composer run setup (see [composer.json](composer.json)).
- Full dev stack (php server + queue listener + pail logs + Vite): composer run dev (uses concurrently).
- Tests: composer run test (clears config then runs artisan test).
- Front-end only: npm run dev or npm run build (see [package.json](package.json)).

## Conventions observed
- Controllers are currently minimal; routes in [routes/web.php](routes/web.php) directly return views for simple pages.
- Asset hot-reload relies on Vite’s hot file in public and Blade @vite checks for build/manifest.json or hot (see [resources/views/welcome.blade.php](resources/views/welcome.blade.php)).
- Vite is configured to ignore storage view cache changes to avoid rebuild loops (see [vite.config.js](vite.config.js)).

## Where to extend
- Add new HTTP endpoints in [routes/web.php](routes/web.php).
- Add controllers under [app/Http/Controllers](app/Http/Controllers) and pair with Blade views under [resources/views](resources/views).
- Add new models under [app/Models](app/Models) with corresponding migrations under [database/migrations](database/migrations).
