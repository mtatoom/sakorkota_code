# sakorkota_code

# 1. Installer les dépendances PHP

composer install

# 2. Générer la clé d'application

php artisan key:generate

# 3. Créer les tables Landlord (Centrale)
php artisan migrate --path=database/migrations/landlord --database=mysql

# 4. Créer les tables Tenant (Boutique modèle)
php artisan migrate --path=database/migrations/tenant --database=mysql_secondaire

# pour chaque table, il faut créer un model
# Modèles pour la base Landlord (Centrale)
php artisan make:model Tenant
php artisan make:model Domain

# Modèles pour la base Tenant (Boutique)
php artisan make:model User
php artisan make:model Category
php artisan make:model Product
php artisan make:model Customer
php artisan make:model Sale
php artisan make:model SaleItem
php artisan make:model StockMovement

# Création des models
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