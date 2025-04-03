<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PosController;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/inventory', [ProductController::class, 'index'])->name('inventory.index');
Route::post('/inventory/add', [ProductController::class, 'store'])->name('product.store');
Route::get('/inventory/{product}', [ProductController::class, 'show'])->name('product.show');
Route::delete('/inventory/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
Route::get('/inventory{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
Route::put('/inventory/{product}', [ProductController::class, 'update'])->name('product.update');

Route::post('/product/{product}/add', [LotController::class, 'store'])->name('lot.store');
Route::get('/inventory/{product}/add', [LotController::class, 'index'])->name('lot.index');
Route::get('/lots/{lot}/edit', [LotController::class, 'editLot'])->name('lot.edit');
Route::put('/lots/{lot}', [LotController::class, 'updateLot'])->name('lot.update');
Route::delete('/lots/{lot}', [LotController::class, 'destroy'])->name('lot.destroy');

Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::post('/cart/add', [PosController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [PosController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [PosController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/checkout', [PosController::class, 'checkout'])->name('checkout');
Route::post('/cart/save-insurance', [PosController::class, 'saveInsuranceData'])->name('cart.saveInsurance');
Route::post('/cart/update-insurance', [PosController::class, 'updateInsurance'])->name('cart.updateInsurance');
Route::get('/insurance-sale', [PosController::class, 'insuranceSale'])->name('insurance.sale');

Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.details');
Route::get('/sales', [SaleController::class, 'index'])->name('sales.history');
Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.delete');
