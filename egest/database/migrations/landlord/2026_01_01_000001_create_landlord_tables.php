<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Table des Tenants (Boutiques clientes)
        Schema::connection('mysql')->create('tenants', function (Blueprint $table) {
            $table->string('id', 191)->primary(); // ex: 'atyket'
            $table->string('name'); // Ancien 'nom_boutique'
            $table->string('db_name');
            $table->string('db_username')->default('root');
            $table->string('db_password')->nullable();
            $table->string('subscription_plan', 50)->default('free');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Table des Domaines
        Schema::connection('mysql')->create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain', 191)->unique(); // Ancien 'domaine'
            $table->string('tenant_id', 191);
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

/*         // 3. Table des Sessions (Standard Laravel)
        Schema::connection('mysql')->create('sessions', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        }); */
    }

    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('domains');
        Schema::connection('mysql')->dropIfExists('tenants');
        Schema::connection('mysql')->dropIfExists('sessions');
    }
};
