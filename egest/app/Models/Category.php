<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // Connexion Tenant dynamique
    protected $connection = 'mysql_secondaire';

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description'
    ];

    /**
     * Une catégorie a plusieurs produits
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
