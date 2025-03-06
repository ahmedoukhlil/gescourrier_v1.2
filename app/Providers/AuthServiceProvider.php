<?php

namespace App\Providers;

use App\Models\CourrierSortant;
use App\Models\CourriersEntrants;
use App\Policies\CourrierEntrantPolicy;
use App\Policies\CourrierSortantPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        CourriersEntrants::class => CourrierEntrantPolicy::class,
        CourrierSortant::class => CourrierSortantPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Définition des gates pour les différentes permissions
        
        // Gestion des utilisateurs (Admin uniquement)
        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });
        
        // Gestion des rôles (Admin uniquement)
        Gate::define('manage-roles', function ($user) {
            return $user->isAdmin();
        });
        
        // Gestion des courriers (Admin, Gestionnaire, Agent)
        Gate::define('manage-courriers', function ($user) {
            return $user->canManageCourriers();
        });
        
        // Visualisation des courriers (Tous les rôles authentifiés)
        Gate::define('view-courriers', function ($user) {
            return $user->canViewCourriers();
        });
        
        // Supprimer des courriers (Admin, Gestionnaire uniquement)
        Gate::define('delete-courriers', function ($user) {
            return $user->hasRole(['admin', 'gestionnaire']);
        });
        
        // Ajouter/éditer des décharges (Admin, Gestionnaire, Agent)
        Gate::define('manage-decharges', function ($user) {
            return $user->canManageCourriers();
        });
    }
}