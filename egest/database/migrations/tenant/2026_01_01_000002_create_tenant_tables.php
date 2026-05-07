<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('email', 191)->unique();
            $table->string('mot_de_passe');
            $table->enum('role', ['admin', 'manager', 'vendeur'])->default('vendeur');
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->string('nom', 100);
            $table->string('slug', 191)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('sku', 100)->unique();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('cible', 191)->nullable();
            $table->decimal('prix_achat', 12, 2)->default(0);
            $table->decimal('prix_vente', 12, 2);
            $table->integer('quantite_stock')->default(0);
            $table->integer('seuil_alerte')->default(5);
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom_complet');
            $table->string('telephone', 20)->nullable();
            $table->string('email', 191)->nullable();
            $table->text('adresse')->nullable();
            $table->decimal('total_depense', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_commande', 50)->unique();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('utilisateur_id')->nullable()->constrained('utilisateurs');
            $table->decimal('total_ht', 12, 2);
            $table->decimal('total_ttc', 12, 2);
            $table->decimal('frais_livraison', 12, 2)->default(0);
            $table->enum('statut_paiement', ['attente', 'paye', 'annule', 'rembourse'])->default('attente');
            $table->enum('statut_livraison', ['preparation', 'expedie', 'livre'])->default('preparation');
            $table->string('mode_paiement', 100)->nullable();
            $table->timestamp('date_vente')->useCurrent();
            $table->timestamps();
        });

        Schema::create('vente_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vente_id')->constrained('ventes')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits');
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 12, 2);
            $table->decimal('total_ligne', 12, 2);
        });

        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite');
            $table->enum('type', ['vente', 'achat', 'retour', 'perte', 'ajustement']);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('commentaire')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
        Schema::dropIfExists('vente_lignes');
        Schema::dropIfExists('ventes');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('produits');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('utilisateurs');
    }
};
