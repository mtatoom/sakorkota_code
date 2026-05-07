<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('nom_boutique');
            $table->string('db_name');
            $table->string('db_username')->nullable();
            $table->string('db_password')->nullable();
            $table->string('plan_abonnement', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('domaines', function (Blueprint $table) {
            $table->id();
            $table->string('domaine', 191)->unique();
            $table->string('tenant_id', 191);
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domaines');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('sessions');
    }
};
