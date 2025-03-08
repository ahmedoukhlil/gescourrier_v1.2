@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Projet de réponse #{{ $draft->id }}</h1>
        
        <div class="flex space-x-2">
            @if(Auth::id() === $draft->user_id)
                <form action="{{ route('lecteur-response-drafts.destroy', $draft) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet de réponse ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Supprimer
                    </button>
                </form>
            @endif
            
            @if(Auth::user()->isLecteur())
                <a href="{{ route('courriers.shared.show', $draft->courrier->shares()->where('shared_with', Auth::id())->first()->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour au courrier
                </a>
            @else
                <a href="{{ route('lecteur-response-drafts.index', $draft->courrier_entrant_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à la liste
                </a>
            @endif
        </div>
    </div>

    <!-- Statut du projet -->
    <div class="mb-6">
        @if($draft->is_reviewed)
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">Ce projet a été examiné par {{ $draft->reviewer->name }} le {{ $draft->reviewed_at->format('d/m/Y à H:i') }}.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5.5a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800">Ce projet est en attente d'examen par un gestionnaire.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Informations du projet -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Informations du projet</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Détails du projet</h3>
                <div class="mt-2 bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 gap-2">
                        <div>
                            <p class="text-xs text-gray-500">Soumis par</p>
                            <p class="font-medium">{{ $draft->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Date de soumission</p>
                            <p class="font-medium">{{ $draft->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Pour le courrier</p>
                            <p class="font-medium">{{ $draft->courrier->numero_ordre }} - {{ $draft->courrier->objet }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">Document soumis</h3>
                <div class="mt-2 p-4 bg-gray-50 rounded-lg">
                    @php
                        $extension = pathinfo($draft->file_path, PATHINFO_EXTENSION);
                        $isPdf = strtolower($extension) === 'pdf';
                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        $isDocument = in_array(strtolower($extension), ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt']);
                    @endphp
                    
                    <div class="flex items-center mb-4">
                        <div class="mr-3">
                            @if($isPdf)
                                <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            @elseif($isImage)
                                <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            @else
                                <svg class="h-8 w-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            @endif
                </div>
            </div>
        </div>
        
        <!-- Commentaires du lecteur -->
        <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Commentaire du lecteur</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                @if($draft->comment)
                    <p class="whitespace-pre-line text-gray-700">{{ $draft->comment }}</p>
                @else
                    <p class="text-gray-500 italic">Aucun commentaire fourni.</p>
                @endif
            </div>
        </div>
        
        <!-- Feedback du gestionnaire (si examiné) -->
        @if($draft->is_reviewed)
        <div>
            <h3 class="text-sm font-medium text-gray-500 mb-2">Feedback du gestionnaire</h3>
            <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                <p class="text-sm text-gray-500 mb-2">Examiné par {{ $draft->reviewer->name }} le {{ $draft->reviewed_at->format('d/m/Y à H:i') }}</p>
                <p class="whitespace-pre-line text-gray-700">{{ $draft->feedback }}</p>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Formulaire d'examen pour les gestionnaires -->
    @if(!$draft->is_reviewed && Auth::user()->canAnnotateCourriers())
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Examiner ce projet de réponse</h2>
        
        <form action="{{ route('lecteur-response-drafts.review', $draft) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="feedback" class="block text-sm font-medium text-gray-700">Feedback</label>
                <textarea id="feedback" name="feedback" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('feedback') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Fournissez un feedback sur le projet de réponse.</p>
                @error('feedback')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150">
                    Valider l'examen
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
                        </div>
                        <div>
                            <p class="text-sm font-medium">{{ basename($draft->file_path) }}</p>
                            <div class="flex space-x-3 mt-1">
                                <a href="{{ route('document.view', $draft->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Ouvrir
                                </a>
                                
                                <a href="{{ route('document.download', $draft->file_path) }}" class="text-green-600 hover:text-green-800 text-sm flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Télécharger
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    @if($isPdf || $isImage)
                    <div class="mt-2 border border-gray-300 rounded-lg overflow-hidden">
                        @if($isPdf)
                            <iframe src="{{ route('document.view', $draft->file_path) }}" class="w-full h-96" frameborder="0"></iframe>
                        @elseif($isImage)
                            <img src="{{ route('document.view', $draft->file_path) }}" alt="Projet de réponse" class="max-w-full max-h-96 mx-auto">
                        @endif
                    </div>
                    @endif