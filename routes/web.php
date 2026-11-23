<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:6,1')->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');

    Route::get('/ingredients', [IngredientController::class, 'index'])->name('ingredients.index');
    Route::get('/ingredients/create', [IngredientController::class, 'create'])->name('ingredients.create');
    Route::post('/ingredients', [IngredientController::class, 'store'])->name('ingredients.store');
    Route::get('/ingredients/{ingredient}/edit', [IngredientController::class, 'edit'])->name('ingredients.edit');
    Route::put('/ingredients/{ingredient}', [IngredientController::class, 'update'])->name('ingredients.update');
});
