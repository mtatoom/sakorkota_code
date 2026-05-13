<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'mysql_secondaire';

    protected $fillable = ['parent_id', 'name', 'slug', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Relation pour gérer les sous-catégories si besoin
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
