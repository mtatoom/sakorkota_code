<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mysql_secondaire')->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('vendeur');
            $table->timestamps();
        });

        Schema::connection('mysql_secondaire')->create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::connection('mysql_secondaire')->create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->decimal('total_spent', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::connection('mysql_secondaire')->create('products', function (Blueprint $table) {
            $table->id();
            // Changement en nullable pour préserver le produit si la catégorie est supprimée
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('sku')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('target')->nullable();
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('sale_price', 15, 2);

            // --- NOUVEAU : Intégration des colonnes pour la gestion des promotions ---
            $table->decimal('promo_price', 15, 2)->nullable();
            $table->dateTime('promo_start_at')->nullable();
            $table->dateTime('promo_end_at')->nullable();
            // ------------------------------------------------------------------------

            $table->integer('stock_quantity')->default(0);
            $table->integer('alert_threshold')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::connection('mysql_secondaire')->create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->decimal('total_excl_tax', 15, 2); // HT
            $table->decimal('total_incl_tax', 15, 2); // TTC
            $table->decimal('delivery_fees', 15, 2)->default(0);
            $table->string('payment_status');
            $table->string('delivery_status');
            $table->string('payment_method');
            $table->dateTime('sale_date');
            $table->timestamps();
        });

        Schema::connection('mysql_secondaire')->create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_item_price', 15, 2); // Quantité x Prix unitaire
        });

        Schema::connection('mysql_secondaire')->create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('type', ['in', 'out', 'adjustment']); // Entrée, Sortie, Ajustement
            $table->string('reference_id')->nullable(); // ID de la vente ou code commande
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::connection('mysql_secondaire')->dropIfExists('stock_movements');
        Schema::connection('mysql_secondaire')->dropIfExists('sale_items');
        Schema::connection('mysql_secondaire')->dropIfExists('sales');
        Schema::connection('mysql_secondaire')->dropIfExists('customers');
        Schema::connection('mysql_secondaire')->dropIfExists('products');
        Schema::connection('mysql_secondaire')->dropIfExists('categories');
        Schema::connection('mysql_secondaire')->dropIfExists('users');
    }
};
