<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('categories.index', compact('categories'));
    }

   public function store(Request $request)
{
    $request->validate([
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
    ]);

    $category = Category::create([
        'name'        => $request->name,
        'slug'        => \Illuminate\Support\Str::slug($request->name),
        'description' => $request->description,
    ]);

    // SI C'EST DU AJAX (Création à la volée depuis le formulaire produit)
    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    // Sinon, comportement normal pour la page classique des catégories
    return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès.');
}

    // MISE À JOUR DE LA CATÉGORIE
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $category->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    // SUPPRESSION DE LA CATÉGORIE
    public function destroy(Category $category)
    {
        // Optionnel : vérifier si des produits sont encore liés à cette catégorie avant de supprimer
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Impossible de supprimer : des produits sont encore liés à cette catégorie.');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }
}