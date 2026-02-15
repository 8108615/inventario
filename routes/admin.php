<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\MovementController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TransferController;
use App\Http\Controllers\Admin\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

//Inventario
Route::resource('categories', CategoryController::class)->except(['show']);


Route::resource('products', ProductController::class)->except(['show']);
Route::post('products/{product}/dropzone', [ProductController::class, 'dropzone'])->name('products.dropzone');

Route::get('products/{product}/kadex', [ProductController::class, 'kardex'])->name('products.kardex');

Route::resource('warehouses', WarehouseController::class)->except(['show']);

//Compras
Route::resource('suppliers', SupplierController::class)->except(['show']);
Route::resource('purchase-orders', PurchaseOrderController::class)->only(['index', 'create']);
Route::resource('purchases', PurchaseController::class)->only(['index', 'create']);

//Ventas
Route::resource('customers', CustomerController::class)->except(['show']);
Route::resource('quotes', QuoteController::class)->only(['index', 'create']);
Route::resource('sales', SaleController::class)->only(['index', 'create']);

//Movimientos
Route::resource('movements', MovementController::class)->only(['index', 'create']);

Route::resource('transfers', TransferController::class)->only(['index', 'create']);


Route::delete('images/{image}', [ImageController::class, 'destroy'])
    ->name('images.destroy');

