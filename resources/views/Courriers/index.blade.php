@extends('layouts.app')

@section('title', 'Gestion des Courriers Entrants')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Courriers Entrants</h1>
        
        <div class="flex space-x-2">
            <!-- Composant modal Livewire pour créer un nouveau courrier -->
            @livewire('create-courrier-modal')
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

    <!-- Composant Livewire pour la liste des courriers -->
    @livewire('courriers-list')
</div>
@endsection
<!-- In resources/views/courriers-sortants/index.blade.php -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Listen for page refresh events from Livewire
        Livewire.on('refreshCourriersSortants', () => {
            // Can add additional JavaScript if needed
        });
        
        // Handle modal events
        window.livewire.on('openUploadModal', function(courrierSortantId) {
            window.livewire.emit('openModal', courrierSortantId);
        });
    });
</script>
@endpush