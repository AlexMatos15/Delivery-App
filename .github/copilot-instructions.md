# Copilot instructions for this repo

## Project overview
- Laravel 12 (PHP ^8.2) app using the default Laravel structure; primary entrypoint is [public/index.php](public/index.php).
- Web requests flow through route files (start with [routes/web.php](routes/web.php)) to controllers in [app/Http/Controllers](app/Http/Controllers), then render Blade views in [resources/views](resources/views).
- Eloquent models live in [app/Models](app/Models), with migrations in [database/migrations](database/migrations) and factories/seeders in [database/factories](database/factories) and [database/seeders](database/seeders).

## Data Model
- **Users**: Multi-role users (client, admin, shop) with authentication and status tracking.
- **Categories**: Product categorization with slug, image, and ordering support.
- **Products**: Shop inventory with pricing, stock, images, and category relationships; belongs to shop (User).
- **Addresses**: User delivery addresses with default address support.
- **Orders**: Complete order tracking with status workflow (pending → confirmed → preparing → out_for_delivery → delivered/cancelled), payment tracking, and shop-customer relationships.
- **OrderItems**: Order line items with product snapshots (name, price) to preserve historical data.

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

## Product & Category Management
- **Categories**: Admin CRUD at `/admin/categories` managed by [app/Http/Controllers/Admin/CategoryController.php](app/Http/Controllers/Admin/CategoryController.php).
  - Automatic slug generation from name.
  - Image upload to `storage/categories`.
  - Activate/deactivate toggle without deletion.
  - Display order field for custom sorting.
- **Products**: Admin CRUD at `/admin/products` managed by [app/Http/Controllers/Admin/ProductController.php](app/Http/Controllers/Admin/ProductController.php).
  - Category relationship (required).
  - Price and promotional_price fields (promotional must be < price).
  - Stock tracking with visual indicators.
  - Image upload to `storage/products`.
  - Featured products flag for homepage display.
  - Activate/deactivate toggle without deletion.
  - Products belong to shop users via `user_id`.
- **Storage**: Symbolic link created via `php artisan storage:link` for public image access at `/storage`.
- **Views**: Blade templates in [resources/views/admin/categories](resources/views/admin/categories) and [resources/views/admin/products](resources/views/admin/products) follow consistent UI pattern with Tailwind CSS.

## Shopping Cart
- **Session-based cart**: Cart data stored in PHP session via [app/Http/Controllers/CartController.php](app/Http/Controllers/CartController.php).
- **Cart operations**: Add products with quantity validation, update quantities (AJAX-enabled), remove items, clear entire cart.
- **Real-time updates**: Cart count badge in navigation updates via JavaScript after cart operations.
- **Price calculation**: Uses current product price (promotional if available) via `Product::getCurrentPrice()`.
- **Stock validation**: Prevents adding more items than available stock; checks on both add and update operations.
- **Multi-shop support**: Tracks shop_id for each cart item to enable future order splitting by shop.
- **Public product catalog**: Browse products at `/products` with category filter, search, and featured products flag.
- **Cart routes**: All cart routes require authentication; available at `/cart/*`.

## Order Management
- **Order creation**: Transform cart into order(s) via [app/Http/Controllers/OrderController.php](app/Http/Controllers/OrderController.php).
- **Multi-shop orders**: Automatically splits cart into separate orders per shop when checking out.
- **Order workflow**: Status progression: pending → confirmed → preparing → out_for_delivery → delivered/cancelled.
- **Address management**: Users must have at least one delivery address to checkout; addresses stored with `label` field.
- **Payment methods**: Supports credit_card, debit_card, pix, cash (payment processing not implemented yet).
- **Stock management**: Decrements product stock on order creation; restores on cancellation.
- **Order history**: Users can view all their orders at `/orders` with status indicators and details.
- **Order details**: Full order view at `/orders/{order}` shows items, delivery address, payment info, and totals.
- **Order cancellation**: Users can cancel pending/confirmed orders; stock is automatically restored.
- **Order number**: Unique auto-generated order number format: `ORD-XXXXXXXX`.
- **Delivery fee**: Fixed at R$ 5.00 per order (hardcoded for now).
- **Authorization**: Users can only view/cancel their own orders; admins can view all orders.

## Address Management
- **CRUD operations**: Full address management at `/addresses` via [app/Http/Controllers/AddressController.php](app/Http/Controllers/AddressController.php).
- **Address fields**: Label, street, number, complement, neighborhood, city, state, zip_code, reference point.
- **Default address**: Users can set one address as default; first address is automatically default.
- **Authorization**: Users can only manage their own addresses.
- **Validation**: Prevents deleting the only address if user has orders; auto-assigns new default when deleting current default.
- **Integration**: Addresses available in checkout page; quick access link in profile page.
- **Views**: List, create, and edit views in [resources/views/addresses](resources/views/addresses) with responsive grid layout.

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
