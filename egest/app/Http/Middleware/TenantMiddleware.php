<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost(); // Ex: zara.localhost

        // 1. Rechercher le domaine dans la BDD Landlord (connexion par défaut 'mysql')
        $domain = DB::connection('mysql')
            ->table('domains')
            ->where('domain', $host)
            ->first();

        if (!$domain) {
            abort(404, "La boutique associée à ce domaine n'existe pas.");
        }

        // 2. Récupérer les informations du Tenant
        $tenant = DB::connection('mysql')
            ->table('tenants')
            ->where('id', $domain->tenant_id)
            ->first();

        if (!$tenant || !$tenant->is_active) {
            abort(403, "Cette boutique est suspendue ou inactive.");
        }

        // 3. Reconfigurer dynamiquement la connexion 'mysql_secondaire'
        Config::set('database.connections.mysql_secondaire.database', $tenant->db_name);
        Config::set('database.connections.mysql_secondaire.username', $tenant->db_username ?? 'root');
        Config::set('database.connections.mysql_secondaire.password', $tenant->db_password ?? '');

        // 4. Purger l'ancienne instance de connexion pour forcer Laravel à utiliser la nouvelle config
        DB::purge('mysql_secondaire');

        return $next($request);
    }
}
