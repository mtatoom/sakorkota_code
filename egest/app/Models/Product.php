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
        'promo_price',       // Nouveau : Prix promotionnel
        'promo_start_at',    // Nouveau : Date de début de la promo
        'promo_end_at',      // Nouveau : Date de fin de la promo
        'stock_quantity',
        'alert_threshold',
        'is_active'
    ];

    protected $casts = [
        'purchase_price' => 'float',
        'sale_price' => 'float',
        'promo_price' => 'float',
        'promo_start_at' => 'datetime', // Convertit automatiquement en objet Carbon
        'promo_end_at' => 'datetime',   // Convertit automatiquement en objet Carbon
        'stock_quantity' => 'integer',
        'alert_threshold' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Accesseur pour obtenir le prix actuel (normal ou promo si active)
     * S'utilise partout dans tes vues via : $product->current_price
     */
    public function getCurrentPriceAttribute(): float
    {
        $now = now(); // Date et heure actuelles de Madagascar

        // On vérifie si une promo est configurée et si on est dans la période définie
        if ($this->promo_price !== null &&
            $this->promo_start_at && $this->promo_start_at->isPast() &&
            $this->promo_end_at && $this->promo_end_at->isFuture()) {
            return $this->promo_price;
        }

        // Sinon, retourne le prix standard
        return $this->sale_price;
    }

    /**
     * Vérifie en un clin d'œil si le produit est en promotion active
     * S'utilise partout dans ton code via : $product->is_on_promo
     */
    public function getIsOnPromoAttribute(): bool
    {
        return $this->current_price === $this->promo_price;
    }

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
