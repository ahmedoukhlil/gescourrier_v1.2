<!-- resources/views/lecteur-response-drafts/exchanges/form.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            @if(Auth::user()->canAnnotateCourriers())
                Ajouter un commentaire au projet de réponse de {{ $draft->user->name }}
            @else
                Répondre au commentaire du gestionnaire
            @endif
        </h1>
        
        <a href="{{ route('lecteur-response-drafts.show', $draft) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour au projet
        </a>
    </div>

    <!-- Informations du projet -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Informations du projet</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Courrier</p>
                <p class="mt-1">{{ $draft->courrier->numero_ordre }} - {{ $draft->courrier->objet }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Soumis par</p>
                <p class="mt-1">{{ $draft->user->name }} le {{ $draft->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Statut</p>
                <p class="mt-1">
                    @if($draft->is_reviewed)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Approuvé
                        </span>
                    @elseif($draft->status === 'revised')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            Révisé - En attente d'examen
                        </span>
                    @elseif($draft->needs_revision)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Révision demandée
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            En attente d'examen
                        </span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Document actuel</p>
                <p class="mt-1">
                    <a href="{{ route('document.view', $draft->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ basename($draft->file_path) }}
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Formulaire d'échange -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">
            @if(Auth::user()->canAnnotateCourriers())
                Ajouter un commentaire
            @else
                Soumettre une révision
            @endif
        </h2>
        
        @if(Auth::user()->canAnnotateCourriers())
            <!-- Formulaire pour les gestionnaires -->
            <form action="{{ route('response-draft-exchanges.store', $draft) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="feedback">
                
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700">Commentaire</label>
                    <textarea id="comment" name="comment" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('comment') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Fournissez vos commentaires sur le projet de réponse.</p>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Action</label>
                    <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="pending">Demander une révision</option>
                        <option value="approved">Approuver le projet</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Choisissez si vous souhaitez approuver le projet ou demander une révision.</p>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150">
                        Envoyer le commentaire
                    </button>
                </div>
            </form>
        @else
            <!-- Formulaire pour les lecteurs -->
            <form action="{{ route('response-draft-exchanges.store', $draft) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="revision">
                
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700">Commentaire</label>
                    <textarea id="comment" name="comment" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('comment') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Expliquez les modifications apportées à votre projet de réponse.</p>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="response_file" class="block text-sm font-medium text-gray-700">Nouvelle version du document</label>
                    <input type="file" id="response_file" name="response_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                    <p class="mt-1 text-sm text-gray-500">Téléchargez votre projet de réponse révisé (formats acceptés : PDF, Word, etc.)</p>
                    @error('response_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150">
                        Soumettre la révision
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection