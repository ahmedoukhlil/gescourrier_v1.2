<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Http\Livewire\CourriersList;
use App\Http\Livewire\CourriersSortantsList;
use App\Http\Livewire\CreateCourrierSortantModal;
use App\Http\Livewire\UploadDechargeModal;
use App\Http\Livewire\NotificationIndicator;
use App\Http\Livewire\NotificationList;
use App\Http\Livewire\NotificationSettings;
use App\Http\Livewire\OfflineNotificationManager;

use Illuminate\Support\Facades\Blade;
use App\Http\Middleware\CheckRole;




class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Livewire::component('courriers-list', CourriersList::class);
        Livewire::component('create-courrier-sortant-modal', CreateCourrierSortantModal::class);
        Livewire::component('courriers-sortants-list', CourriersSortantsList::class);
        Livewire::component('upload-decharge-modal', \App\Http\Livewire\UploadDechargeModal::class);      
        Blade::component('layouts.app', \App\View\Components\AppLayout::class);
        // Enregistrement du middleware 'role'
        Route::aliasMiddleware('role', CheckRole::class);
        Livewire::component('notification-indicator', NotificationIndicator::class);
        Livewire::component('notification-list', NotificationList::class);
        Livewire::component('notification-settings', NotificationSettings::class);
        Livewire::component('offline-notification-manager', OfflineNotificationManager::class);
 
 

    }
}