<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // On crée l'administrateur principal du Landlord sans passer par la factory
        // pour garder un contrôle strict sur son mot de passe et son rôle.
        User::create([
            'name' => 'Mijo RABE',
            'email' => 'mijo@example.com',
            'password' => Hash::make(1234), // Remplace par ton mot de passe de test
            'role' => 'admin', // Assure la distinction avec les futurs utilisateurs des boutiques
        ]);
    }
}