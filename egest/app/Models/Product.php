<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    // Force ce modèle à utiliser la base de données du Tenant reconfigurée
    protected $connection = 'mysql_secondaire';

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'target', // Présent dans ton schéma d'origine
        'purchase_price',
        'sale_price',
        'stock_quantity',
        'alert_threshold',
        'is_active'
    ];

    protected $casts = [
        'purchase_price' => 'float',
        'sale_price' => 'float',
        'stock_quantity' => 'integer',
        'alert_threshold' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Relation avec la catégorie du produit
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relation avec les mouvements de stock (Table: stock_movements)
     * Utile pour l'onglet "Traceability" de ton menu
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'product_id');
    }

    /**
     * Relation avec les éléments de ventes (Table: sale_items)
     * Utile pour la gestion des commandes et des factures
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'product_id');
    }
}
