<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        // 1. SÉCURITÉ : Ignorer la racine de la plateforme
        if ($host === 'localhost' || $host === '127.0.0.1') {
            return $next($request);
        }

        // 2. RECHERCHE ISOLÉE : On utilise 'mysql_landlord' pour ne pas corrompre 'mysql'
        $domain = DB::connection('mysql_landlord')
            ->table('domains')
            ->where('domain', $host)
            ->first();

        if (!$domain) {
            abort(404, "Aucune boutique enregistrée avec le domaine : " . $host);
        }

        $tenant = DB::connection('mysql_landlord')
            ->table('tenants')
            ->where('id', $domain->tenant_id)
            ->first();

        if (!$tenant || !$tenant->is_active) {
            abort(403, "Cette boutique est indisponible.");
        }

        // 3. BASCULEMENT UNIQUE : On configure la connexion par défaut 'mysql' pour le reste du cycle
        Config::set('database.connections.mysql.database', $tenant->db_name);
        Config::set('database.connections.mysql.username', $tenant->db_username ?? 'root');
        Config::set('database.connections.mysql.password', $tenant->db_password ?? '');

        // Déconnexion pour forcer la reconnexion au prochain appel sur la bonne BDD
        DB::disconnect('mysql');

        return $next($request);
    }
}
