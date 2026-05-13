<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SatuanBarangController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Route::redirect('/dashboard', '/', 301);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return view('pages.index');
    })->name('home');

    // user route
    Route::get('/user', [UserController::class, 'index'])->name('admin.user.index');
    Route::post('/user/update-profile', [UserController::class, 'updateProfile'])->name('admin.user.updateProfile');
    Route::post('/user/update-password', [UserController::class, 'updatePassword'])->name('admin.user.updatePassword');
    Route::post('/user/update-photo', [UserController::class, 'updatePhoto'])->name('admin.user.updatePhoto');

    // Master Data Barang
    Route::group(['prefix' => 'barang'], function () {
        Route::get('/export', [BarangController::class, 'export'])->name('barang.export');
        Route::get('/barang/search', [BarangController::class, 'searchBarang'])->name('barang.search');
        Route::resource('satuan', SatuanBarangController::class)->except(['create', 'edit']);
        Route::resource('kategori', KategoriBarangController::class)->except(['create', 'edit']);
    });
    Route::resource('barang', BarangController::class)->except(['create', 'edit']);

    // Barang Masuk
    Route::post('/barang-masuk/quick-barang', [BarangMasukController::class, 'quickStoreBarang'])->name('barang-masuk.quick-store');
    Route::get('/barang-masuk/{id}/pdf', [BarangMasukController::class, 'exportPdf'])->name('barang-masuk.pdf');
    Route::resource('barang-masuk', BarangMasukController::class)->except(['edit', 'update', 'destroy']);

    // Barang Keluar
    Route::get('/barang-keluar/{id}/pdf', [BarangKeluarController::class, 'exportPdf'])->name('barang-keluar.pdf');
    Route::resource('barang-keluar', BarangKeluarController::class)->except(['edit', 'update', 'destroy']);

});
