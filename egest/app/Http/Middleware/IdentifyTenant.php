<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Gère la requête entrante et connecte la BDD tenant.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost(); // Récupère 'zara.localhost'

        // 1. Recherche du domaine dans la table 'domains' (Bdd Landlord / connexion 'mysql')
        $domain = DB::connection('mysql')
            ->table('domains')
            ->where('domain', $host)
            ->first();

        if (!$domain) {
            abort(404, "Aucune boutique enregistrée avec le domaine : " . $host);
        }

        // 2. Récupération du tenant lié via 'tenant_id'
        $tenant = DB::connection('mysql')
            ->table('tenants')
            ->where('id', $domain->tenant_id)
            ->first();

        if (!$tenant) {
            abort(404, "Le compte de la boutique est introuvable.");
        }

        if (!$tenant->is_active) {
            abort(403, "Cette boutique (Tenant) est actuellement désactivée.");
        }

        // 3. Configuration dynamique de la connexion 'mysql_secondaire'
        Config::set('database.connections.mysql_secondaire.database', $tenant->db_name);
        Config::set('database.connections.mysql_secondaire.username', $tenant->db_username ?? 'root');
        Config::set('database.connections.mysql_secondaire.password', $tenant->db_password ?? '');

        // 4. Purge pour appliquer les nouveaux accès
        DB::purge('mysql_secondaire');

        return $next($request);
    }
}
