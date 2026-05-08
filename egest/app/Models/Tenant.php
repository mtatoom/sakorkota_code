<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    //Définir la connexion à utiliser pour ce modèle
    protected $connection = 'mysql';
    //dire à laravel que la clé primaire est de type string
    protected $keyType = 'string';
    //dire à laravel que la clé primaire n'est pas auto-incrémentée
    public $incrementing = false;
    //lister les champs qui peuvent être remplis en masse
    protected $fillable = [
        'id',
        'nom_boutique',
        'db_name',
        'db_username',
        'db_password',
        'plan_abonnement',
    ];
    /**
     * Relation: un client peut avoir plusieurs nom de domaine
     */
    
}
