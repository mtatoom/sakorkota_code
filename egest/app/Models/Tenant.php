<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class Tenant extends Model
{
    // Reste sur mysql car la création d'une boutique se fait depuis la plateforme globale
    protected $connection = 'mysql';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'db_name',
        'db_username',
        'db_password',
        'subscription_plan',
        'is_active'
    ];

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    protected static function booted()
    {
        static::creating(function ($tenant) {
            if (empty($tenant->id)) {
                $tenant->id = Str::slug($tenant->name);
            }

            $cleanSlug = str_replace('-', '_', $tenant->id);
            $tenant->db_name = 'venduix_' . $cleanSlug;

            if (empty($tenant->db_username)) {
                $tenant->db_username = config('database.connections.mysql.username');
            }
            if (empty($tenant->db_password)) {
                $tenant->db_password = config('database.connections.mysql.password');
            }

            if (empty($tenant->subscription_plan)) {
                $tenant->subscription_plan = 'free';
            }
        });

        static::created(function ($tenant) {
            if ($tenant->db_name) {
                // A. Créer la base de données brute
                DB::statement("CREATE DATABASE IF NOT EXISTS `{$tenant->db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

                // B. Configuration de la connexion secondaire temporaire pour exécuter la migration du client
                config(['database.connections.mysql_secondaire.database' => $tenant->db_name]);

                DB::disconnect('mysql_secondaire');
                DB::purge('mysql_secondaire');
                DB::reconnect('mysql_secondaire');

                // C. Lancement des migrations spécifiques au tenant
                Artisan::call('migrate', [
                    '--database'       => 'mysql_secondaire',
                    '--path'           => 'database/migrations/tenant', // <-- S'assurer de ne migrer QUE le dossier tenant
                    '--force'          => true,
                    '--no-interaction' => true,
                ]);
            }
        });
    }
}
