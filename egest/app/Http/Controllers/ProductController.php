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

    /**
     * Afficher le formulaire de modification
     */
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
            'promo_price'     => 'nullable|numeric|min:0|lt:sale_price', // Doit être inférieur au prix normal
            'promo_start_at'  => 'nullable|required_with:promo_price|date',
            'promo_end_at'    => 'nullable|required_with:promo_price|date|after:promo_start_at', // Date de fin après le début
            'stock_quantity'  => 'required|integer|min:0',
            'alert_threshold' => 'required|integer|min:0',
            'target'          => 'nullable|string|max:255',
            'description'     => 'nullable|string',
        ]);

        // Nettoyage des valeurs de promotion si aucun prix promo n'est défini
        $promoPrice = $request->filled('promo_price') ? $request->promo_price : null;
        $promoStart = $promoPrice ? $request->promo_start_at : null;
        $promoEnd = $promoPrice ? $request->promo_end_at : null;

        // Création du produit via la connexion 'mysql_secondaire'
        Product::create([
            'category_id'     => $request->category_id,
            'sku'             => 'PROD-' . strtoupper(Str::random(6)), // Génération automatique du SKU
            'name'            => $request->name,
            'description'     => $request->description,
            'target'          => $request->target,
            'purchase_price'  => $request->purchase_price,
            'sale_price'      => $request->sale_price,
            'promo_price'     => $promoPrice,
            'promo_start_at'  => $promoStart,
            'promo_end_at'    => $promoEnd,
            'stock_quantity'  => $request->stock_quantity,
            'alert_threshold' => $request->alert_threshold,
            'is_active'       => true,
        ]);

        return redirect()->route('products.index')->with('success', 'Produit ajouté avec succès à votre inventaire.');
    }

    /**
     * Mettre à jour un produit existant en BDD Tenant
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|exists:mysql_secondaire.categories,id',
            'purchase_price'  => 'required|numeric|min:0',
            'sale_price'      => 'required|numeric|min:0',
            'promo_price'     => 'nullable|numeric|min:0|lt:sale_price',
            'promo_start_at'  => 'nullable|required_with:promo_price|date',
            'promo_end_at'    => 'nullable|required_with:promo_price|date|after:promo_start_at',
            'stock_quantity'  => 'required|integer|min:0',
            'alert_threshold' => 'required|integer|min:0',
            'target'          => 'nullable|string|max:255',
            'description'     => 'nullable|string',
        ]);

        // Nettoyage pour éviter que de vieilles dates restent stockées sans prix promo associé
        $promoPrice = $request->filled('promo_price') ? $request->promo_price : null;
        $promoStart = $promoPrice ? $request->promo_start_at : null;
        $promoEnd = $promoPrice ? $request->promo_end_at : null;

        $product->update([
            'category_id'     => $request->category_id,
            'name'            => $request->name,
            'description'     => $request->description,
            'target'          => $request->target,
            'purchase_price'  => $request->purchase_price,
            'sale_price'      => $request->sale_price,
            'promo_price'     => $promoPrice,
            'promo_start_at'  => $promoStart,
            'promo_end_at'    => $promoEnd,
            'stock_quantity'  => $request->stock_quantity,
            'alert_threshold' => $request->alert_threshold,
        ]);

        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Appliquer une promotion par pourcentage sur une sélection de produits (Mass Promo)
     * Protège automatiquement la marge commerciale en vérifiant le prix d'achat.
     */
    public function promoMass(Request $request)
    {
        $request->validate([
            'ids'            => 'required|string',
            'percentage'     => 'required|numeric|min:1|max:99',
            'promo_start_at' => 'required|date',
            'promo_end_at'   => 'required|date|after:promo_start_at',
        ]);

        $ids = explode(',', $request->input('ids'));
        $products = Product::whereIn('id', $ids)->get();

        $appliedCount = 0;
        $skippedCount = 0;

        foreach ($products as $product) {
            // Calcul de la valeur de la réduction et arrondi (supérieur pour préserver l'Ariary)
            $discountAmount = $product->sale_price * ($request->percentage / 100);
            $calculatedPromoPrice = ceil($product->sale_price - $discountAmount);

            // Règle de sécurité stricte : prix d'achat < prix promo < prix de vente
            if ($calculatedPromoPrice > $product->purchase_price && $calculatedPromoPrice < $product->sale_price) {
                $product->update([
                    'promo_price'    => $calculatedPromoPrice,
                    'promo_start_at' => $request->promo_start_at,
                    'promo_end_at'   => $request->promo_end_at,
                ]);
                $appliedCount++;
            } else {
                // Le pourcentage demandé aurait vendu le produit à perte ou à un prix non conforme
                $skippedCount++;
            }
        }

        // Retour utilisateur contextuel et précis
        if ($appliedCount === 0 && $skippedCount > 0) {
            return redirect()->route('products.index')->with('error', "Aucun produit n'a été mis en promotion. La réduction de {$request->percentage}% aurait entraîné des ventes à perte.");
        }

        if ($skippedCount > 0) {
            return redirect()->route('products.index')->with('warning', "Campagne activée pour {$appliedCount} produit(s). Cependant, {$skippedCount} produit(s) ont été ignorés car le prix remisé passait en dessous de leur prix d'achat.");
        }

        return redirect()->route('products.index')->with('success', "La promotion de {$request->percentage}% a été appliquée avec succès à la sélection.");
    }

    /**
     * Annuler ou mettre fin immédiatement aux promotions sur une sélection de produits
     */
    public function cancelPromoMass(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
        ]);

        $ids = explode(',', $request->input('ids'));

        // On remet simplement les champs de promotion à null pour toute la sélection
        Product::whereIn('id', $ids)->update([
            'promo_price'    => null,
            'promo_start_at' => null,
            'promo_end_at'   => null,
        ]);

        return redirect()->route('products.index')->with('success', 'La promotion a été interrompue avec succès pour les produits sélectionnés.');
    }

    /**
     * Supprimer un produit
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produit supprimé avec succès.');
    }

    /**
     * Suppression groupée (Massive)
     */
    public function destroyMass(Request $request)
    {
        if ($request->filled('ids')) {
            $ids = explode(',', $request->input('ids'));
            Product::whereIn('id', $ids)->delete();
        }
        return redirect()->route('products.index')->with('success', 'Sélection supprimée avec succès.');
    }
}