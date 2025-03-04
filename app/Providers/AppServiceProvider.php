<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use App\Http\Livewire\CourriersList;
use App\Http\Livewire\CourriersSortantsList;
use App\Http\Livewire\CreateCourrierSortantModal;
use App\Http\Livewire\UploadDechargeModal;


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

    }
}