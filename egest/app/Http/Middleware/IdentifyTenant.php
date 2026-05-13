<?php

namespace App\Http\Middleware; // <-- TRÈS IMPORTANT : Vérifie cette ligne

use Closure;
use Illuminate\Http\Request;
use App\Models\Domain;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $domain = Domain::where('domain', $host)->with('tenant')->first();

        if (!$domain) {
            abort(404, "Boutique introuvable.");
        }

        $tenant = $domain->tenant;

        Config::set('database.connections.mysql_secondaire.database', $tenant->db_name);
        Config::set('database.connections.mysql_secondaire.username', $tenant->db_username);
        Config::set('database.connections.mysql_secondaire.password', $tenant->db_password);

        DB::purge('mysql_secondaire');
        DB::reconnect('mysql_secondaire');

        return $next($request);
    }
}