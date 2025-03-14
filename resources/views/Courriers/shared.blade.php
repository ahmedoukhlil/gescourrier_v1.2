@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Courrier Partagé #{{ $share->courrier->numero_ordre }}</h1>
        
        <div class="flex space-x-2">
            @if(!$share->is_read)
                <form action="{{ route('courriers.mark-as-read', $share->courrier) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Marquer comme lu
                    </button>
                </form>
            @endif
            
            <a href="{{ route('courriers.shared') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Statut de lecture -->
    <div class="mb-6">
        @if($share->is_read)
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="h-6 w-6 text-green-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Courrier lu</p>
                        <p class="text-sm">Vous avez marqué ce courrier comme lu le {{ $share->updated_at->format('d/m/Y à H:i') }}.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="h-6 w-6 text-yellow-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Courrier non lu</p>
                        <p class="text-sm">Ce courrier n'a pas encore été marqué comme lu.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Informations du courrier -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Informations du courrier</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Détails du courrier</h3>
                <div class="mt-2 bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 gap-2">
                        <div>
                            <p class="text-xs text-gray-500">Numéro d'ordre</p>
                            <p class="font-medium">{{ $share->courrier->numero_ordre }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Expéditeur</p>
                            <p class="font-medium">{{ $share->courrier->expediteur }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Objet</p>
                            <p class="font-medium">{{ $share->courrier->objet }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Type</p>
                            <p>
                                @if($share->courrier->type == 'urgent')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Urgent
                                    </span>
                                @elseif($share->courrier->type == 'confidentiel')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Confidentiel
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Normal
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Date de réception</p>
                            <p class="font-medium">{{ $share->courrier->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">Informations de partage</h3>
                <div class="mt-2 bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 gap-2">
                        <div>
                            <p class="text-xs text-gray-500">Partagé par</p>
                            <p class="font-medium">{{ $share->sharer->name }} ({{ $share->sharer->service }})</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Date de partage</p>
                            <p class="font-medium">{{ $share->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Statut</p>
                            <p>
                                @if($share->is_read)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Lu
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Non lu
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section pour le projet de réponse - maintenant au bon niveau -->
    @if(Auth::user()->isLecteur())
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Projet de réponse</h2>
        
        @php
            $existingDraft = App\Models\LecteurResponseDraft::where('courrier_entrant_id', $share->courrier->id)
                                ->where('user_id', Auth::id())
                                ->first();
        @endphp
        
        @if($existingDraft)
            <div class="bg-blue-50 p-4 border-l-4 border-blue-500 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Vous avez déjà soumis un projet de réponse pour ce courrier le {{ $existingDraft->created_at->format('d/m/Y à H:i') }}.
                        </p>
                        <div class="mt-2">
                            <a href="{{ route('lecteur-response-drafts.show', $existingDraft) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-blue-300 transition ease-in-out duration-150">
                                Voir mon projet
                            </a>
                        </div>
                        
                        @if($existingDraft->is_reviewed)
                            <p class="mt-2 text-sm text-blue-700">
                                <span class="font-medium">Statut :</span> Examiné par {{ $existingDraft->reviewer->name }}
                            </p>
                        @else
                            <p class="mt-2 text-sm text-blue-700">
                                <span class="font-medium">Statut :</span> En attente d'examen
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        
        <p class="mb-4">
            En tant que lecteur, vous pouvez soumettre un projet de réponse pour ce courrier. Ce projet sera examiné par un gestionnaire qui pourra vous fournir un feedback.
        </p>
        
        <div class="flex justify-center">
            <a href="{{ route('lecteur-response-drafts.create', $share->courrier) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                {{ $existingDraft ? 'Soumettre un nouveau projet' : 'Soumettre un projet de réponse' }}
            </a>
        </div>
    </div>
    @endif

    <!-- Document joint avec visualisation intégrée -->
    @if($share->courrier->document_path)
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Document joint</h2>
            
            @php
                $extension = pathinfo($share->courrier->document_path, PATHINFO_EXTENSION);
                $isPdf = strtolower($extension) === 'pdf';
                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                $isDocument = in_array(strtolower($extension), ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt']);
            @endphp
            
            <div class="border border-gray-200 rounded-lg p-4 mb-4">
                <div class="flex items-center">
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
                    <div>
                        <p class="text-sm font-medium">{{ basename($share->courrier->document_path) }}</p>
                        <div class="flex space-x-3 mt-1">
                            <a href="{{ route('document.view', $share->courrier->document_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Ouvrir dans un nouvel onglet
                            </a>
                            
                            <a href="{{ route('document.download', $share->courrier->document_path) }}" class="text-green-600 hover:text-green-800 text-sm flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Télécharger
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <!-- Visualiseur de document intégré -->
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    @if($isPdf)
                        <div class="w-full" style="height: 800px;">
                            <iframe src="{{ route('document.view', $share->courrier->document_path) }}" class="w-full h-full" frameborder="0"></iframe>
                        </div>
                    @elseif($isImage)
                        <div class="flex justify-center p-4 bg-gray-100">
                            <img src="{{ route('document.view', $share->courrier->document_path) }}" alt="Document" class="max-w-full max-h-96 object-contain">
                        </div>
                    @else
                        <div class="p-6 bg-gray-100 text-center">
                            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-lg font-medium text-gray-700">Aperçu non disponible</p>
                            <p class="text-sm text-gray-500 mt-1">Le type de document ne peut pas être affiché directement.</p>
                            <a href="{{ route('document.view', $share->courrier->document_path) }}" target="_blank" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Ouvrir le document
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Projets de réponse (pour les gestionnaires/admins) -->
    @if(Auth::user()->canAnnotateCourriers() || Auth::user()->isAdmin() || Auth::user()->isGestionnaire())
    @php
        $responseDrafts = $share->courrier->responseDrafts()->with('user')->get();
        $pendingDrafts = $responseDrafts->where('is_reviewed', false);
        $reviewedDrafts = $responseDrafts->where('is_reviewed', true);
    @endphp

        @if($responseDrafts->count() > 0)
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Projets de réponse des lecteurs</h2>
                    <a href="{{ route('lecteur-response-drafts.index', $share->courrier) }}" class="text-indigo-600 hover:text-indigo-900">
                        Voir tous les projets ({{ $responseDrafts->count() }})
                    </a>
                </div>
                
                @if($pendingDrafts->count() > 0)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-yellow-800 bg-yellow-100 px-3 py-2 rounded-t-md">En attente d'examen ({{ $pendingDrafts->count() }})</h3>
                        <div class="border border-yellow-200 rounded-b-md p-4 space-y-3">
                            @foreach($pendingDrafts as $draft)
                                <div class="flex justify-between items-center pb-3 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                    <div>
                                        <p class="font-medium">{{ $draft->user->name }}</p>
                                        <p class="text-sm text-gray-500">Soumis le {{ $draft->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('document.view', $draft->file_path) }}" target="_blank" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md text-sm hover:bg-blue-200">
                                            Voir le document
                                        </a>
                                        <a href="{{ route('lecteur-response-drafts.show', $draft) }}" class="px-3 py-1 bg-green-100 text-green-700 rounded-md text-sm hover:bg-green-200">
                                            Examiner
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if($reviewedDrafts->count() > 0)
                    <div>
                        <h3 class="text-sm font-medium text-green-800 bg-green-100 px-3 py-2 rounded-t-md">Déjà examinés ({{ $reviewedDrafts->count() }})</h3>
                        <div class="border border-green-200 rounded-b-md p-4 space-y-3">
                            @foreach($reviewedDrafts->take(3) as $draft)
                                <div class="flex justify-between items-center pb-3 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                    <div>
                                        <p class="font-medium">{{ $draft->user->name }}</p>
                                        <p class="text-sm text-gray-500">Examiné par {{ $draft->reviewer->name }} le {{ $draft->reviewed_at->format('d/m/Y') }}</p>
                                    </div>
                                    <a href="{{ route('lecteur-response-drafts.show', $draft) }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">
                                        Détails
                                    </a>
                                </div>
                            @endforeach
                            
                            @if($reviewedDrafts->count() > 3)
                                <div class="text-center pt-2">
                                    <a href="{{ route('lecteur-response-drafts.index', $share->courrier) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                        Voir {{ $reviewedDrafts->count() - 3 }} projet(s) examiné(s) supplémentaire(s)...
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Projets de réponse des lecteurs</h2>
                <div class="bg-gray-50 p-4 rounded-md text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun projet de réponse</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Aucun lecteur n'a encore soumis de projet de réponse pour ce courrier.
                    </p>
                </div>
            </div>
        @endif
    @endif

    <!-- Annotation -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Annotation du gestionnaire</h2>
        
        @if($share->annotation)
            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r-lg">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="font-medium">{{ $share->annotation->annotator->name }}</span>
                        <span class="text-sm text-gray-500 ml-2">{{ $share->annotation->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <div class="whitespace-pre-line text-gray-700">{{ $share->annotation->annotation }}</div>
            </div>
        @else
            <p class="text-gray-500 italic">Aucune annotation n'a été ajoutée à ce partage.</p>
        @endif
    </div>
</div>
@endsection