# sakorkota_code

# 1. Installer les dépendances PHP

composer install

# 2. Générer la clé d'application

php artisan key:generate

# 3. Créer les tables Landlord (Centrale)

php artisan migrate --path=database/migrations/landlord --database=mysql

# 4. Créer les tables Tenant (Boutique modèle)

php artisan migrate --path=database/migrations/tenant --database=mysql_secondaire
