@extends('layouts.app')

@section('title', 'Gestion des Courriers Sortants')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Courriers Sortants</h1>
        
        <div class="flex space-x-2">
            <!-- Composant Livewire pour créer un nouveau courrier sortant -->
            @livewire('create-courrier-sortant-modal')
        </div>
    </div>

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
    
    <!-- Composant Livewire pour la liste des courriers sortants -->
    @livewire('courriers-sortants-list')
    
    <!-- Composant pour gérer l'upload des décharges -->
    @livewire('upload-decharge-modal')
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Utilisation de window.livewire.on (avec un "l" minuscule)
        window.livewire.on('openUploadModal', function(courrierSortantId) {
            // Émission de l'événement vers le composant upload-decharge-modal
            window.livewire.emit('openModal', courrierSortantId);
        });
    });
</script>
@endpush

@endsection