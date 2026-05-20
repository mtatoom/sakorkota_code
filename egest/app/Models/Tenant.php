<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $connection = 'mysql';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['id', 'name', 'db_name', 'db_username', 'db_password', 'subscription_plan', 'is_active'];

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    //***********CREATION AUTO DE BDD********************/
    protected static function booted()
    {
        static::created(function ($tenant) {
            if ($tenant->db_name) {
                // 1. Créer la base de données brute
                \Illuminate\Support\Facades\DB::statement("CREATE DATABASE IF NOT EXISTS `{$tenant->db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

                // 2. Configurer la connexion secondaire
                config(['database.connections.mysql_secondaire.database' => $tenant->db_name]);
                \Illuminate\Support\Facades\DB::purge('mysql_secondaire');

                // 3. Lancer les migrations
                // On retire le '--path' pour que Laravel exécute TOUTES les migrations disponibles, c'est plus sûr pour l'instant
                \Illuminate\Support\Facades\Artisan::call('migrate', [
                    '--database' => 'mysql_secondaire',
                    '--force' => true,
                ]);
            }
        });
    }
}
