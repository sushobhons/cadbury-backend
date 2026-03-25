<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'showRegisterPage'])->name('home');
Route::post('/register', [UserController::class, 'registerUser'])->name('register');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/', [AdminController::class, 'login']);

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/users/data', [AdminController::class, 'usersData'])->name('admin.users.data');
        Route::get('/users/export', [AdminController::class, 'exportUsers'])->name('admin.users.export');
        Route::get('/photo-uploads', [AdminController::class, 'photoUploads'])->name('admin.photo-uploads');
        Route::get('/photo-uploads/data', [AdminController::class, 'photoUploadsData'])->name('admin.photo-uploads.data');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});


