<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

//MODULO PUBLICO

Route::redirect('/', '/login')->name('home');

// AUTENTICAR
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

//MODULO ADMINISTRATIVO
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    //CRUD de categorías: /admin/categorias
    Route::resource('categorias', CategoryController::class)
        ->except('show')
        ->parameters(['categorias' => 'category'])
        ->names('admin.categories');
});