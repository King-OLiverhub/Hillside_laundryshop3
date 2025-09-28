<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\InventoryController;

// API Controllers
use App\Http\Controllers\Api\ServiceController as ApiServiceController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\Api\ApiCustomerController;
use App\Http\Controllers\Api\ApiAddonController;

Route::get('/', fn() => redirect('/dashboard'));

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // POS
    Route::prefix('pos')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('pos.index');
        Route::post('/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    });

    // Customers
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'page'])->name('customers.page');
    });

    // Inventory
    Route::get('/inventory', [InventoryController::class, 'page'])->name('inventory.page');

    // Sales Report
    Route::prefix('sales')->group(function () {
        Route::get('/', [SalesReportController::class, 'page'])->name('sales.page');
        Route::get('/report', [SalesReportController::class, 'index'])->name('sales.report');
    });
});

// API Routes (public for now, consider adding auth)
Route::prefix('api')->group(function () {
    // Customers API
    Route::prefix('customers')->group(function () {
        Route::get('/', [ApiCustomerController::class, 'index']);
        Route::post('/', [ApiCustomerController::class, 'store']);
    });

    // Services API
    Route::prefix('services')->group(function () {
        Route::get('/', [ApiServiceController::class, 'index']);
        Route::get('/{service}', [ApiServiceController::class, 'show']);
        Route::post('/', [ApiServiceController::class, 'store']);
        Route::put('/{service}', [ApiServiceController::class, 'update']);
        Route::delete('/{service}', [ApiServiceController::class, 'destroy']);
    });

    // Products API
    Route::prefix('products')->group(function () {
        Route::get('/', [ApiProductController::class, 'index']);
        Route::get('/{product}', [ApiProductController::class, 'show']);
        Route::post('/', [ApiProductController::class, 'store']);
        Route::put('/{product}', [ApiProductController::class, 'update']);
        Route::delete('/{product}', [ApiProductController::class, 'destroy']);
        Route::post('/use', [App\Http\Controllers\ProductController::class, 'useProduct']);
    });

    // Addons API
    Route::get('/addons', [ApiAddonController::class, 'index']);
    Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
});