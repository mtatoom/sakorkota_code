<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        // Génération d'un prix d'achat réaliste (ex: entre 5 000 et 50 000)
        $purchasePrice = $this->faker->numberBetween(5, 50) * 1000;
        // Le prix de vente doit laisser une marge
        $salePrice = $purchasePrice * $this->faker->randomFloat(2, 1.3, 1.8);

        // Simulation : 30% de chance que le produit soit actuellement en promotion
        $isOnPromo = $this->faker->boolean(30);
        $promoPrice = $isOnPromo ? ($salePrice * 0.8) : null; // -20% de réduction
        $promoStart = $isOnPromo ? now()->subDays(2) : null;
        $promoEnd = $isOnPromo ? now()->addDays($this->faker->numberBetween(5, 15)) : null;

        return [
            // Lie automatiquement le produit à une catégorie existante ou en crée une nouvelle
            'category_id'     => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'sku'             => 'PROD-' . strtoupper(Str::random(6)),
            'name'            => $this->faker->randomElement(['Robe', 'Pantalon', 'T-shirt', 'Veste', 'Sac à main']) . ' ' . $this->faker->colorName(),
            'description'     => $this->faker->paragraph(1),
            'target'          => $this->faker->randomElement(['Homme', 'Femme', 'Enfant', 'Unisex']),
            'purchase_price'  => $purchasePrice,
            'sale_price'      => $salePrice,

            // Les champs de promotion fraîchement ajoutés à ta base
            'promo_price'     => $promoPrice,
            'promo_start_at'  => $promoStart,
            'promo_end_at'    => $promoEnd,

            'stock_quantity'  => $this->faker->numberBetween(0, 100),
            'alert_threshold' => $this->faker->randomElement([3, 5, 10]),
            'is_active'       => $this->faker->boolean(90), // 90% de produits actifs
        ];
    }
}
