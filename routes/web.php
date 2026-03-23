<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('admin-dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('admin-dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function(){
    // ROUTE FOR ADMIN
    Route::get('list-product', [AdminController::class, 'getListProduct'])->name('list-product');
    Route::post('store-product', [AdminController::class, 'store'])->name('store-product');
    Route::delete('product/{productId}/delete', [AdminController::class, 'deleteProduct'])->name('product.delete');
    Route::get('product/{productId}/edit', [AdminController::class, 'getProductBy'])->name('product.edit');

    // ROUTE FOR CASHIER
});

require __DIR__.'/auth.php';
