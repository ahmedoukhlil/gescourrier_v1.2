<!-- resources/views/courriers-sortants/index.blade.php -->
@extends('layouts.app')

@section('title', 'Gestion des Courriers Sortants')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Courriers Sortants</h1>
        
        <div class="flex space-x-2">
            <!-- Composant Livewire pour ouvrir le modal de création de courrier sortant -->
            @livewire('create-courrier-sortant-modal')
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Composant Livewire pour la liste des courriers sortants -->
    @livewire('courriers-sortants-list')
</div>
@endsection