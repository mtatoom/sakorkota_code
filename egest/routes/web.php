<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
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


Route::middleware(['tenant'])->group(function () {
    Route::get('/test-db', function () {
        return response()->json([
            'status' => 'success',
            'database' => DB::connection('mysql_secondaire')->getDatabaseName()
        ]);
    });

    //********************************************************/
    // Route temporaire pour vérifier la connexion à la base de données du tenant
    Route::get('/add-product', function () {
        // 1. S'assurer qu'une catégorie existe (obligatoire : NOT NULL)
        $category = App\Models\Category::firstOrCreate([
            'name' => 'Vêtements',
            'slug' => 'vetements',
        ]);

        // 2. Création du produit avec tous les champs obligatoires
        $product = App\Models\Product::create([
            'category_id'    => $category->id,
            'sku'            => 'PROD-' . strtoupper(Str::random(5)),
            'name'           => 'Smartphone Test ' . request()->getHost(),
            'description'    => 'Chemise manche courte',
            'purchase_price' => 20000, // Obligatoire : NOT NULL
            'sale_price'     => 10000,  // Obligatoire : NOT NULL
            'stock_quantity' => 10,
            'alert_threshold' => 3,
            'is_active'      => true,
        ]);

        return response()->json([
            'message'  => 'Produit créé !',
            'boutique' => request()->getHost(),
            'base_de_données' => DB::connection('mysql_secondaire')->getDatabaseName(),
            'produit'  => $product->name
        ]);
    });
    //*******************************************************/

    // Ta route de test précédente
    Route::get('/add-product', function () { /* ... */
    });

    // La nouvelle route pour afficher la liste
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
});


require __DIR__ . '/auth.php';
