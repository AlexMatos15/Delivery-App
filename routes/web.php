<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Loja\DashboardController as LojaDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

// Public product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT ROUTE (Global)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', DashboardRedirectController::class)->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (All Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my-profile', [ProfileController::class, 'show'])->name('profile.show');
    
    // Address routes (accessible by all authenticated users)
    Route::resource('addresses', AddressController::class)->except(['show']);
    Route::patch('/addresses/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.set-default');
    
    // Order routes (accessible by all authenticated users)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });
});

/*
|--------------------------------------------------------------------------
| CLIENTE ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'is_cliente'])->prefix('cliente')->name('cliente.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [OrderController::class, 'index'])->name('dashboard');
    
    // Cart routes
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
        Route::patch('/update/{product}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{product}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::get('/count', [CartController::class, 'count'])->name('count');
    });
});

/*
|--------------------------------------------------------------------------
| LOJA ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'is_loja'])->prefix('loja')->name('loja.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [LojaDashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderManagementController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderManagementController::class, 'show'])->name('show');
        Route::patch('/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('update-status');
        Route::patch('/{order}/payment', [OrderManagementController::class, 'updatePaymentStatus'])->name('update-payment');
    });
    
    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::patch('/{user}/toggle', [UserManagementController::class, 'toggleActive'])->name('toggle');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    });
    
    // Categories Management
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::patch('/categories/{category}/toggle', [CategoryController::class, 'toggleActive'])->name('categories.toggle');
    
    // Products Management
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::patch('/products/{product}/toggle', [AdminProductController::class, 'toggleActive'])->name('products.toggle');
});

require __DIR__.'/auth.php';
