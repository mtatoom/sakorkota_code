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
# Créer le premiet client de test dans Landlord
php artisan tinker
# puis
// Créer le client
$tenant = App\Models\Tenant::create([
    'id' => 'atyket',
    'name' => 'A-Tyket Shop',
    'db_name' => 'sakorkota',
    'db_username' => 'root',
    'db_password' => '',
    'subscription_plan' => 'premium'
]);

// Lui assigner un domaine
App\Models\Domain::create([
    'domain' => 'atyket.localhost',
    'tenant_id' => 'atyket'
]);

# Configurer ton environnement local (Hosts)
Ton navigateur ne sait pas que atyket.localhost doit pointer vers ton ordinateur. Sur Windows, il faut modifier le fichier hosts.

Ouvre le Bloc-notes en Administrateur.

Ouvre le fichier : C:\Windows\System32\drivers\etc\hosts.

Ajoute cette ligne à la fin :
127.0.0.1 atyket.localhost

# Tester la connexion dynamique
Créons une route rapide pour vérifier que Laravel change bien de base de données selon l'URL.

Ouvre routes/web.php et ajoute ceci :

PHP
use App\Models\Product;
use Illuminate\Support\Facades\DB;

Route::middleware(['tenant'])->group(function () {
    Route::get('/test-db', function () {
        // Affiche le nom de la base de données actuellement connectée
        $dbName = DB::connection('mysql_secondaire')->getDatabaseName();
        
        return "Connecté à la boutique. Base de données active : " . $dbName;
    });
});