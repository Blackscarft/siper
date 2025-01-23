<?php

use App\Http\Controllers\ProfileController;
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
});
