<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\LoginController; 
use App\Http\Controllers\UserController; 
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\exportEaxcel;
use App\Http\Controllers\ProductsExport;
use App\Http\Controllers\UsercExport;



Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'loginAuth'])->name('loginAuth');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::prefix('/admin')->name('admin.')->group(function(){
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

            Route::get('/products', [AdminController::class, 'productIndex'])->name('product.index');
            Route::get('/product/create', [AdminController::class, 'productCreate'])->name('product.create');
            Route::put('/product/{product}/update-stok', [AdminController::class, 'productUpdateStok'])->name('product.update-stok');
            Route::put('/product/stok/{id}', [AdminController::class, 'productStok'])->name('product.stok');
            Route::post('/product/store', [AdminController::class, 'productStore'])->name('product.store');
            Route::get('/product/{product}/edit', [AdminController::class, 'productEdit'])->name('product.edit');
            Route::put('/product/{product}/update', [AdminController::class, 'productUpdate'])->name('product.update');
            Route::delete('/product/{product}', [AdminController::class, 'productDestroy'])->name('product.delete');
            
            Route::get('/users', [AdminController::class, 'userIndex'])->name('user.index');
            Route::get('/user/create', [AdminController::class, 'userCreate'])->name('user.create');
            Route::post('/user/store', [AdminController::class, 'userStore'])->name('user.store');
            Route::get('/user/{user}/edit', [AdminController::class, 'userEdit'])->name('user.edit');
            Route::put('/user/{user}/update', [AdminController::class, 'userUpdate'])->name('user.update');
            Route::delete('/user/{user}', [AdminController::class, 'userDestroy'])->name('user.destroy');
            Route::get('/pembelian', [AdminController::class, 'pembelianIndex'])->name('pembelian.index');
            Route::get('/pdf/{id}', [AdminController::class, 'exportPDF'])->name('exportPDF');
            Route::get('/pembelian/export-excel', [AdminController::class, 'exportExcel'])->name('pembelian.export-excel');   
            Route::get('/user/export', [AdminController::class, 'exportUsersExcel'])->name('user.export');
            Route::get('/products/export', [AdminController::class, 'exportProductExcel'])->name('product.export');
        });
    });

    Route::middleware(['auth', 'role:petugas'])->prefix('/petugas')->name('petugas.')->group(function () {

        Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
        Route::get('/products', [PetugasController::class, 'productIndex'])->name('product.index');
        Route::get('/pembelian', [PetugasController::class, 'pembelianIndex'])->name('pembelian.index');
        Route::get('/pembelian/create', [PetugasController::class, 'pembelianCreate'])->name('pembelian.create');
        Route::post('/pembelian/checkout', [PetugasController::class, 'pembelianCheckout'])->name('pembelian.checkout');
        Route::post('/pembelian/store', [PetugasController::class, 'pembelianStore'])->name('pembelian.store');
        Route::post('/pembelian/member', [PetugasController::class, 'pembelianMemberStore'])->name('pembelian.memberstore');
        Route::get('/pembelian/detail-print/{id}', [PetugasController::class, 'pembelianDetailPrint'])->name('pembelian.detail-print');
        Route::get('/pdf/{id}', [PetugasController::class, 'exportPDF'])->name('exportPDF');
        Route::get('/pembelian/export-excel', [PetugasController::class, 'exportExcel'])->name('pembelian.export-excel');
    });
    