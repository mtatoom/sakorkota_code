<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    //Définir la connexion à utiliser pour ce modèle
    protected $connection = 'mysql';
}
