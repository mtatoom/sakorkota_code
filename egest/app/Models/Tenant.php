<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class Tenant extends Model
{
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

    //*********** AUTO-GÉNÉRATION ET CRÉATION DE BDD ********************/
    protected static function booted()
    {
        // 1. Avant la création : Génération automatique des données techniques
        static::creating(function ($tenant) {
            // Si l'id n'est pas fourni, on utilise le slug du nom de l'entreprise (ex: "Ma Bijouterie" -> "ma-bijouterie")
            if (empty($tenant->id)) {
                $tenant->id = Str::slug($tenant->name);
            }

            // Génération du nom de la base de données avec le préfixe "venduix_"
            $cleanSlug = str_replace('-', '_', $tenant->id);
            $tenant->db_name = 'venduix_' . $cleanSlug;

            // Remplissage des identifiants par défaut s'ils sont vides
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

        // 2. Après la création : Initialisation physique de la base de données du client
        static::created(function ($tenant) {
            if ($tenant->db_name) {
                // A. Créer la base de données brute
                DB::statement("CREATE DATABASE IF NOT EXISTS `{$tenant->db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

                // B. Configurer dynamiquement la connexion secondaire
                config(['database.connections.mysql_secondaire.database' => $tenant->db_name]);
                DB::purge('mysql_secondaire');

                // C. Lancer les migrations
                Artisan::call('migrate', [
                    '--database' => 'mysql_secondaire',
                    '--force'    => true,
                ]);
            }
        });
    }
}
