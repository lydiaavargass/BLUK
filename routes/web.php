<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $loNuevo = \App\Models\Product::with('category')
        ->where('is_active', true)
        ->latest()
        ->take(4)
        ->get();

    $masVendidos = \App\Models\Product::with('category')
        ->where('is_active', true)
        ->orderBy('stock', 'desc')
        ->take(4)
        ->get();

    return view('welcome', compact('loNuevo', 'masVendidos'));
})->name('home');

// Catálogo público (accesible sin autenticación)
Route::get('/catalogo', [ProductController::class, 'index'])->name('products.index');
Route::get('/catalogo/{product}', [ProductController::class, 'show'])->name('products.show');

// Carrito (accesible sin autenticación)
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito', [CartController::class, 'store'])->name('cart.store');
Route::patch('/carrito/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{productId}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pedidos (solo usuarios autenticados)
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pedidos/{order}', [OrderController::class, 'show'])->name('orders.show');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('productos', App\Http\Controllers\Admin\ProductController::class)->names('products');
});

require __DIR__.'/auth.php';
