<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Add necessary model imports
use App\Models\User; // Add necessary model imports
use Illuminate\Support\Facades\Hash; // If hashing passwords

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Création des rôles de base
        $roles = [
            [
                'name' => 'Administrateur',
                'slug' => 'admin',
                'description' => 'Accès complet à toutes les fonctionnalités'
            ],
            [
                'name' => 'Gestionnaire',
                'slug' => 'gestionnaire',
                'description' => 'Gestion des courriers entrants et sortants'
            ],
            [
                'name' => 'Agent',
                'slug' => 'agent',
                'description' => 'Enregistrement des courriers et des décharges'
            ],
            [
                'name' => 'Lecteur',
                'slug' => 'lecteur',
                'description' => 'Accès en lecture seule aux courriers'
            ]
        ];

        foreach ($roles as $role) {
            // First, check if the role already exists
            if (!Role::where('slug', $role['slug'])->exists()) {
                Role::create($role);
            }
    }
}
}
