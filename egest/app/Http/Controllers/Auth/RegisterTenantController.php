<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class RegisterTenantController extends Controller
{
    public function showForm()
    {
        return view('welcome');
    }

    public function register(Request $request)
    {
        // Validation des données d'inscription globale
        $request->validate([
            'company_name' => 'required|string|max:255|unique:tenants,name',
            'admin_name'   => 'required|string|max:255',
            'admin_email'  => 'required|string|email|max:255',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        // 1. Créer le tenant (Déclenche uniquement la création de la base de données brute via le modèle)
        $tenant = Tenant::create([
            'name' => $request->company_name,
        ]);

        // Création du domaine associé dans la base Landlord
        $tenant->domains()->create([
            'domain' => $tenant->id . '.localhost',
        ]);

        // 2. Configuration dynamique de la connexion secondaire sur la nouvelle base du client
        config(['database.connections.mysql_secondaire.database' => $tenant->db_name]);

        // On isole et réinitialise l'état de la connexion en mémoire pour éviter tout conflit
        DB::disconnect('mysql_secondaire');
        DB::purge('mysql_secondaire');
        DB::reconnect('mysql_secondaire');

        try {
            // 3. Exécuter les migrations en ciblant EXCLUSIVEMENT ton dossier 'tenant'
            Artisan::call('migrate', [
                '--database'       => 'mysql_secondaire',
                '--path'           => 'database/migrations/tenant', // <-- Cible ton sous-dossier d'infrastructure client
                '--force'          => true,                         // Évite les demandes de confirmation en arrière-plan
                '--no-interaction' => true,                         // Désactive le verrouillage interactif
            ]);

            // Sécurité : Forcer le rafraîchissement des métadonnées de tables fraîchement créées
            DB::connection('mysql_secondaire')->reconnect();

            // 4. Insérer l'administrateur dans la table 'users' du tenant (qui vient d'être migrée)
            DB::connection('mysql_secondaire')->table('users')->insert([
                'name'       => $request->admin_name,
                'email'      => $request->admin_email,
                'password'   => Hash::make($request->password),
                'role'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        } catch (\Exception $e) {
            // Permet de remonter précisément l'erreur si une ligne plante dans le processus interne
            throw $e;
        }

        // 5. Redirection vers le sous-domaine de l'espace client sur le port 8000
        $redirectUrl = "http://" . $tenant->id . ".localhost:8000/login";

        return redirect()->away($redirectUrl)->with('success', 'Votre espace de gestion a été configuré avec succès !');
    }
}
