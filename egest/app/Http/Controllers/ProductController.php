<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // On récupère tous les produits du client avec leur catégorie
        $products = Product::with('category')->get();

        // On renvoie la vue "index" située dans le dossier "products"
        return view('products.index', compact('products'));
    }
}
