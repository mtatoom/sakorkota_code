<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $connection = 'mysql_secondaire';

    // On précise que seul created_at existe
    const UPDATED_AT = null;

    protected $fillable = ['product_id', 'quantity', 'type', 'reference_id', 'comment'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
