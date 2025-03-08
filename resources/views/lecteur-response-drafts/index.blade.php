@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Projets de réponse pour le courrier #{{ $courrier->numero_ordre }}</h1>
        
        <a href="{{ route('courriers.show', $courrier) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour au courrier
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Informations du courrier -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Informations du courrier</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Numéro d'ordre</p>
                <p class="mt-1">{{ $courrier->numero_ordre }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Expéditeur</p>
                <p class="mt-1">{{ $courrier->expediteur }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Date de réception</p>
                <p class="mt-1">{{ $courrier->created_at->format('d/m/Y') }}</p>
            </div>
            <div class="md:col-span-3">
                <p class="text-sm font-medium text-gray-500">Objet</p>
                <p class="mt-1">{{ $courrier->objet }}</p>
            </div>
        </div>
    </div>

    <!-- Liste des projets de réponse -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold">Projets de réponse soumis</h2>
            <p class="text-sm text-gray-500 mt-1">Liste des projets de réponse soumis par les lecteurs pour ce courrier.</p>
        </div>
        
        @if($drafts->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Soumis par</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de soumission</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($drafts as $draft)
                        <tr class="hover:bg-gray-50 {{ $draft->is_reviewed ? '' : 'bg-yellow-50' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $draft->user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $draft->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $draft->created_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $draft->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($draft->is_reviewed)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Examiné
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Par {{ $draft->reviewer->name }}
                                    </div>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        En attente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('document.view', $draft->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                    <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Voir
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('lecteur-response-drafts.show', $draft) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Détails</a>
                                
                                @if(!$draft->is_reviewed)
                                    <a href="#review-form-{{ $draft->id }}" class="text-green-600 hover:text-green-900" onclick="document.getElementById('review-form-{{ $draft->id }}').scrollIntoView({behavior: 'smooth'});">
                                        Examiner
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Formulaires d'examen pour les projets non examinés -->
            @foreach($drafts->where('is_reviewed', false) as $draft)
                <div id="review-form-{{ $draft->id }}" class="p-6 mt-4 border-t bg-gray-50">
                    <h3 class="text-lg font-semibold mb-4">Examiner le projet de {{ $draft->user->name }}</h3>
                    
                    <form action="{{ route('lecteur-response-drafts.review', $draft) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="feedback-{{ $draft->id }}" class="block text-sm font-medium text-gray-700">Feedback</label>
                            <textarea id="feedback-{{ $draft->id }}" name="feedback" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('feedback') }}</textarea>
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
            @endforeach
        @else
            <div class="p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun projet de réponse</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Aucun lecteur n'a encore soumis de projet de réponse pour ce courrier.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection