# sakorkota_code

# 1. Installer les dépendances PHP

composer install

# 2. Générer la clé d'application

php artisan key:generate

# 3. Créer les tables Landlord (Centrale)

php artisan migrate --path=database/migrations/landlord --database=mysql

# 4. Créer les tables Tenant (Boutique modèle)

## php artisan migrate --path=database/migrations/tenant --database=mysql_secondaire

# pour chaque table, il faut créer un model

# Pour le Landlord (Base centrale)
php artisan make:model Tenant
php artisan make:model Domaine

# Pour le Tenant (Base boutique)
php artisan make:model Utilisateur
php artisan make:model Categorie
php artisan make:model Produit
php artisan make:model Client
php artisan make:model Vente
php artisan make:model VenteLigne
php artisan make:model MouvementStock

# Il faut bien préciser la connexion par défaut dans app/Models/Tenant.php

mysql ou mysql_secondaire
protected $connection ='mysql'

# lister les champs que Laravel a le droit de remplir

protected $fillable = ['id','nom_boutique',db_name, db_password]
# il faut forcer le nom de table
protected $table=ma_boutique