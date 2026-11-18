<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// MODULO PUBLICO
Route::get('/', [PublicController::class, 'home'])->name('home');

// AUTENTICACION
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// MODULO ADMINISTRATIVO (protegido con middleware auth)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    // CRUD de categorias: /admin/categorias
    Route::resource('categorias', CategoryController::class)
        ->except('show')
        ->parameters(['categorias' => 'category'])
        ->names('admin.categories');
});