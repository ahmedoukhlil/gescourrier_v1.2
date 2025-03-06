@extends('layouts.app')

@section('title', 'Gestion des Courriers Sortants')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Courriers Sortants</h1>
        
        <div class="flex space-x-2">
            <!-- Component to create a new outgoing mail -->
            @livewire('create-courrier-sortant-modal')
        </div>
    </div>

    <!-- Flash messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    
    <!-- Main Livewire component -->
    @livewire('courriers-sortants-list')
    
    <!-- Discharge upload modal -->
    @livewire('upload-decharge-modal')
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Écouter les événements de rafraîchissement de la page
        window.livewire.on('courrierSortantCreated', () => {
            console.log('Courrier sortant créé avec succès');
            // Reload pour être sûr que les données sont à jour
            // Livewire.emit('$refresh');
        });
        
        window.livewire.on('dechargeUploaded', () => {
            console.log('Décharge uploadée avec succès');
            // Reload pour être sûr que les données sont à jour
            // Livewire.emit('$refresh');
        });
        
        // Gérer les événements du modal
        window.livewire.on('openModal', (courrierSortantId) => {
            console.log('Ouverture du modal pour le courrier ID:', courrierSortantId);
        });
    });
</script>
@endpush