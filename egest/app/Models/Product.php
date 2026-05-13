<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mysql_secondaire';
    protected $fillable = ['category_id', 'sku', 'name', 'description', 'target', 'purchase_price', 'sale_price', 'stock_quantity', 'alert_threshold', 'is_active'];

    public function category() { return $this->belongsTo(Category::class); }
}
