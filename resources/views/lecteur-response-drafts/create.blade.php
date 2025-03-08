@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Projet de réponse pour le courrier #{{ $courrier->numero_ordre }}</h1>
        
        <a href="{{ route('courriers.shared.show', $share->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour au courrier
        </a>
    </div>

    <!-- Informations du courrier -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Informations du courrier</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Numéro d'ordre</p>
                <p class="mt-1">{{ $courrier->numero_ordre }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Expéditeur</p>
                <p class="mt-1">{{ $courrier->expediteur }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm font-medium text-gray-500">Objet</p>
                <p class="mt-1">{{ $courrier->objet }}</p>
            </div>
        </div>
    </div>

    <!-- Projet de réponse existant -->
    @if($existingDraft)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    Vous avez déjà soumis un projet de réponse pour ce courrier le {{ $existingDraft->created_at->format('d/m/Y à H:i') }}.
                </p>
                <div class="mt-2">
                    <a href="{{ route('lecteur-response-drafts.show', $existingDraft) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:border-yellow-300 focus:shadow-outline-yellow active:bg-yellow-300 transition ease-in-out duration-150">
                        Voir mon projet
                    </a>
                </div>
                <p class="mt-2 text-sm text-yellow-700">Si vous soumettez un nouveau projet, il remplacera le précédent.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Formulaire de projet de réponse -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Soumettre un projet de réponse</h2>
        
        <form action="{{ route('lecteur-response-drafts.store', $courrier) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label for="comment" class="block text-sm font-medium text-gray-700">Commentaire (optionnel)</label>
                <textarea id="comment" name="comment" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('comment') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Vous pouvez ajouter des explications ou des commentaires concernant votre projet de réponse.</p>
                @error('comment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="response_file" class="block text-sm font-medium text-gray-700">Document de réponse</label>
                <input type="file" id="response_file" name="response_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                <p class="mt-1 text-sm text-gray-500">Téléchargez votre projet de réponse (formats acceptés : PDF, Word, etc.)</p>
                @error('response_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150">
                    Soumettre le projet de réponse
                </button>
            </div>
        </form>
    </div>
</div>
@endsection