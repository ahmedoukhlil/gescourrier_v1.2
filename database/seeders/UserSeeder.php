<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer l'admin
        $admin = User::create([
            'name' => 'Administrateur',
            'email' => 'admin@example.com',
            'password' => Hash::make('password')
        ]);
        
        // Attribuer le rôle admin
        $admin->roles()->attach(Role::where('slug', 'admin')->first());
        
        // Créer le gestionnaire
        $gestionnaire = User::create([
            'name' => 'Gestionnaire',
            'email' => 'gestionnaire@example.com',
            'password' => Hash::make('password')
        ]);
        
        // Attribuer le rôle gestionnaire
        $gestionnaire->roles()->attach(Role::where('slug', 'gestionnaire')->first());
        
        // Créer l'agent
        $agent = User::create([
            'name' => 'Agent',
            'email' => 'agent@example.com',
            'password' => Hash::make('password')
        ]);
        
        // Attribuer le rôle agent
        $agent->roles()->attach(Role::where('slug', 'agent')->first());
        
        // Créer le lecteur
        $lecteur = User::create([
            'name' => 'Lecteur',
            'email' => 'lecteur@example.com',
            'password' => Hash::make('password')
        ]);
        
        // Attribuer le rôle lecteur
        $lecteur->roles()->attach(Role::where('slug', 'lecteur')->first());
    }
}