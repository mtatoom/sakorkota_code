<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $connection = 'mysql_secondaire';

    protected $table = 'stock_movements';

    protected $fillable = [
        'product_id',
        'type', // 'IN' (Entrée/Achat) ou 'OUT' (Vente/Perte)
        'quantity',
        'reference_type', // 'sale', 'purchase', 'adjustment'
        'reference_id',
        'description'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
