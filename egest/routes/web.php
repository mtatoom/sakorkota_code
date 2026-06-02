<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\RegisterTenantController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. ROUTES DE LA PLATEFORME GLOBALE (LANDLORD)
|--------------------------------------------------------------------------
| Ces routes ne s'exécutent QUE sur le domaine principal (localhost).
*/
Route::domain('localhost')->group(function () {

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/register-store', [RegisterTenantController::class, 'showForm'])->name('tenant.register');
    Route::post('/register-store', [RegisterTenantController::class, 'register'])->name('tenant.register.store');

});

/*
|--------------------------------------------------------------------------
| 2. ROUTES DES BOUTIQUES CLIENTS (TENANT)
|--------------------------------------------------------------------------
| Ces routes s'appliquent à tous les sous-domaines. Le middleware global
| 'IdentifyTenant' s'occupe déjà de basculer sur la bonne base de données.
*/

// Route Dashboard : Extraction et agrégation des données du Tenant courant
Route::get('/dashboard', function () {
    // 1. Récupération de tous les produits du tenant pour minimiser les requêtes SQL
    $products = Product::with('category')->get();

    // 2. Calcul des indicateurs de performance (KPIs)
    $totalProducts = $products->count();

    $lowStockCount = $products->filter(function ($product) {
        return $product->stock_quantity <= $product->alert_threshold;
    })->count();

    $estimatedValue = $products->sum(function ($product) {
        return $product->sale_price * $product->stock_quantity;
    });

    // 3. Extraction sélective des 5 derniers produits ajoutés pour l'historique récent
    $recentProducts = Product::with('category')
        ->latest()
        ->take(5)
        ->get();

    // 4. Injection des variables compilées dans la vue dashboard.blade.php
    return view('dashboard', compact('totalProducts', 'lowStockCount', 'estimatedValue', 'recentProducts'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 1. Routes classiques Produits
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/add-product', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// 2. Actions groupées
Route::delete('/products-mass-delete', [ProductController::class, 'destroyMass'])->name('products.destroyMass');
Route::post('/products-mass-promo', [ProductController::class, 'promoMass'])->name('products.promoMass');
Route::post('/products-mass-cancel-promo', [ProductController::class, 'cancelPromoMass'])->name('products.cancelPromoMass');

// 3. Routes avec paramètres dynamiques {product}
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

// 4. Routes Catégories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

/*
|--------------------------------------------------------------------------
| 3. INCLUSION DES ROUTES D'AUTHENTIFICATION
|--------------------------------------------------------------------------
| Crucial : Elles doivent être accessibles sur les sous-domaines pour
| que les clients puissent se connecter à leur espace.
*/
require __DIR__ . '/auth.php';