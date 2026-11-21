<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EvidenceController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PostImageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// MODULO PUBLICO
Route::get('/', [PublicController::class, 'home'])->name('home');

Route::get('/entradas', [PublicController::class, 'index'])->name('public.posts');

Route::get('/categorias/{slug}', [PublicController::class, 'category']) ->name('public.category');

Route::get('/entradas/{slug}', [PublicController::class, 'show']) ->name('public.post');

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

    // CRUD de entradas: /admin/entradas
    Route::resource('entradas', PostController::class)
        ->except('show')
        ->parameters(['entradas' => 'post'])
        ->names('admin.posts');

    // Imagenes de la galeria
    Route::patch('imagenes/{image}', [PostImageController::class, 'update'])->name('admin.images.update');
    Route::delete('imagenes/{image}', [PostImageController::class, 'destroy'])->name('admin.images.destroy');

    // Evidencias de asistencia
    Route::post('entradas/{post}/evidencias', [EvidenceController::class, 'store'])->name('admin.evidences.store');
    Route::delete('evidencias/{evidence}', [EvidenceController::class, 'destroy'])->name('admin.evidences.destroy');
});