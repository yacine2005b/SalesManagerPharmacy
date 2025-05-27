<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ActivitylogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PerscriptionController;

use Illuminate\Support\Facades\Route;

// Public routes
Route::get("/", [DashboardController::class, "index"])->name('welcome');

// Routes for managing inventory (pharmacist role)
Route::middleware('role:pharmacist,admin')->group(function () {
    Route::get('/inventory', [ProductController::class, 'index'])->name('inventory.index');
Route::get('/inventory/search', [ProductController::class, 'search'])->name('inventory.search');
Route::get('/inventory/clear-search', [ProductController::class, 'clearSearch'])->name('inventory.clearSearch');
    Route::post('/inventory/add', [ProductController::class, 'store'])->name('product.store');
    Route::get('/inventory/{product}', [ProductController::class, 'show'])->name('product.show');
    Route::delete('/inventory/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::get('/inventory/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/inventory/{product}', [ProductController::class, 'update'])->name('product.update');


Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::post('/inventory/{product}/add', [LotController::class, 'store'])->name('lot.store');
    Route::get('/inventory/{product}/add', [LotController::class, 'index'])->name('lot.index');
    Route::get('/lots/{lot}/edit', [LotController::class, 'editLot'])->name('lot.edit');
    Route::put('/lots/{lot}', [LotController::class, 'updateLot'])->name('lot.update');
    Route::get('/lots/{id}/print-barcode', [LotController::class, 'printBarcode'])->name('lot.printBarcode');
    Route::delete('/lots/{lot}', [LotController::class, 'destroy'])->name('lot.destroy');
});

// Routes for sales and POS (cashier role)
Route::middleware('role:cashier,admin,pharmacist')->group(function () {
    Route::post('/cart/smart-add', [CartController::class, 'smartAdd'])->name('cart.smartAdd');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/save-insurance', [CartController::class, 'saveInsuranceData'])->name('cart.saveInsurance');
    Route::post('/cart/update-insurance', [CartController::class, 'updateInsurance'])->name('cart.updateInsurance');
    Route::post('/cart/switch-sale-type', [CartController::class, 'switchSaleType'])->name('cart.switchSaleType');
    Route::post('/checkout', [PosController::class, 'checkout'])->name('checkout');

    Route::get('/pos', [PosController::class, 'index'])->name('pos.normal');
    Route::get('/pos/prescription', [PosController::class, 'prescriptionSale'])->name('pos.prescription');
    Route::get('/pos/insurance', [PosController::class, 'insuranceSale'])->name('pos.insurance');

    Route::post('/load-prescription-to-cart', [PosController::class, 'loadPrescriptionToCart'])->name('pos.loadPrescriptionToCart');

    Route::get('/sales/{saleSession}', [SaleController::class, 'saleDetails'])->name('sales.details');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.history');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.delete');
    Route::get('/sale/{sale}', [SaleController::class, 'show'])->name('sale.show');

    Route::post('/sales/session/start', [SaleSessionController::class, 'startSession'])->name('sales.session.start');
    Route::post('/sales/session/{session}/end', [SaleSessionController::class, 'endSession'])->name('sales.session.end');
});

// Routes for admin (admin role)
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/logs', [ActivitylogController::class, 'index'])->name('admin.activityLog');
    Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::get('/products/search', [PerscriptionController::class, 'search'])->name('products.search');
    Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
});

// Routes for prescriptions (accessible by admin and pharmacist)
Route::middleware('role:admin,pharmacist')->group(function () {
    Route::get('/perscriptions', [PerscriptionController::class, 'index'])->name('prescription.index');
    Route::post('/perscriptions', [PerscriptionController::class, 'store'])->name('prescription.store');
    Route::get('/perscriptions/{prescription}', [PerscriptionController::class, 'show'])->name('prescription.show');
    Route::delete('/perscriptions/{prescription}', [PerscriptionController::class, 'destroy'])->name('prescription.destroy');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
