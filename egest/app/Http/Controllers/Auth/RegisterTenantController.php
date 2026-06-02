<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterTenantController extends Controller
{
    // Afficher le formulaire public sur la page d'accueil
    public function showForm()
    {
        return view('welcome'); // Ou une vue dédiée à l'inscription
    }

    public function register(Request $request)
    {
        // CORRECTION : Validation sur la colonne 'name' de la table tenants
        $request->validate([
            'company_name' => 'required|string|max:255|unique:tenants,name',
            'admin_name'   => 'required|string|max:255',
            'admin_email'  => 'required|string|email|max:255',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        // 1. Créer le tenant (déclenche automatiquement l'événement booted() et crée la bdd venduix_...)
        // CORRECTION : Assignation sur le champ 'name' attendu par ton $fillable
        $tenant = Tenant::create([
            'name' => $request->company_name,
        ]);

        // CORRECTION : Création du domaine associé dans ta table 'domains' du Landlord
        $tenant->domains()->create([
            'domain' => $tenant->id . '.localhost', // Donne par exemple : ma-boutique.localhost
        ]);

        // 2. Se connecter temporairement à sa nouvelle base pour y injecter l'administrateur
        config(['database.connections.mysql_secondaire.database' => $tenant->db_name]);
        DB::purge('mysql_secondaire');

        // 3. Insérer l'utilisateur dans la table users du tenant
        DB::connection('mysql_secondaire')->table('users')->insert([
            'name'       => $request->admin_name,
            'email'      => $request->admin_email,
            'password'   => Hash::make($request->password),
            'role'       => 'admin', // Configure le rôle en administrateur de boutique
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Redirection vers son espace dédié avec son sous-domaine tout neuf !
        // CORRECTION : Utilisation de $tenant->id (qui contient le slug propre) suivi de ton hôte local
        $redirectUrl = "http://" . $tenant->id . ".localhost:8000/login";

        return redirect()->away($redirectUrl)->with('success', 'Votre espace de gestion a été configuré avec succès !');
    }
}
