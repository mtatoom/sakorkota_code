<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Afficher la liste des produits (Index)
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        // Eager loading de la catégorie liée
        $query = Product::with('category');

        // Application des filtres d'affichage de ton interface
        if ($filter === 'active') {
            $query->where('is_active', true)->where('stock_quantity', '>', 0);
        } elseif ($filter === 'out_of_stock') {
            $query->where('stock_quantity', '<=', 0);
        }

        $products = $query->latest()->get();

        return view('products.index', compact('products', 'filter'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all(); // Nécessaire pour alimenter le select réactif

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Enregistrer un produit en BDD Tenant
     */
    public function store(Request $request)
    {
        // Validation ciblée sur la connexion du sous-domaine en cours
        $request->validate([
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|exists:mysql_secondaire.categories,id',
            'purchase_price'  => 'required|numeric|min:0',
            'sale_price'      => 'required|numeric|min:0',
            'stock_quantity'  => 'required|integer|min:0',
            'alert_threshold' => 'required|integer|min:0',
            'target'          => 'nullable|string|max:255',
            'description'     => 'nullable|string',
        ]);

        // Création du produit via la connexion 'mysql_secondaire'
        Product::create([
            'category_id'     => $request->category_id,
            'sku'             => 'PROD-' . strtoupper(Str::random(6)), // Génération automatique du SKU
            'name'            => $request->name,
            'description'     => $request->description,
            'target'          => $request->target,
            'purchase_price'  => $request->purchase_price,
            'sale_price'      => $request->sale_price,
            'stock_quantity'  => $request->stock_quantity,
            'alert_threshold' => $request->alert_threshold,
            'is_active'       => true,
        ]);
        return redirect()->route('products.index')->with('success', 'Produit ajouté avec succès à votre inventaire.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index');
    }

    public function destroyMass(\Illuminate\Http\Request $request)
    {
        if ($request->filled('ids')) {
            $ids = explode(',', $request->input('ids'));
            Product::whereIn('id', $ids)->delete();
        }
        return redirect()->route('products.index');
    }
}