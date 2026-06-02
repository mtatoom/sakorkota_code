<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['web', 'tenant'])->group(function () {
    // 1. Routes classiques
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/add-product', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    // 2. ROUTE ACTIONS GROUPÉES (À placer impérativement AVANT les routes avec paramètres dynamiques)
    Route::delete('/products-mass-delete', [ProductController::class, 'destroyMass'])->name('products.destroyMass');
    Route::post('/products-mass-promo', [ProductController::class, 'promoMass'])->name('products.promoMass');
    Route::post('/products-mass-cancel-promo', [ProductController::class, 'cancelPromoMass'])->name('products.cancelPromoMass');

    // 3. Routes avec paramètres dynamiques {product}
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Routes Catégories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});


require __DIR__ . '/auth.php';