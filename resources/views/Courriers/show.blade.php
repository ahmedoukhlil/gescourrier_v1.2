@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Courrier #{{ $courrier->numero_ordre }}</h1>
        
        <div class="flex space-x-2">
            @if(Auth::user()->canAnnotateCourriers())
                <a href="{{ route('courriers.annotations.create', $courrier) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Annoter et partager
                </a>
            @endif
            
            @if(Auth::user()->canManageCourriers())
                <a href="{{ route('courriers.edit', $courrier) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
            @endif
            
            @if(Auth::user()->isLecteur())
                <a href="{{ route('courriers.shared') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </a>
            @else
                <a href="{{ route('courriers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </a>
            @endif
        </div>
    </div>

    <!-- Information du courrier -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Informations du courrier</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Détails principaux</h3>
                <div class="mt-2 bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 gap-2">
                        <div>
                            <p class="text-xs text-gray-500">Numéro d'ordre</p>
                            <p class="font-medium">{{ $courrier->numero_ordre }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Expéditeur</p>
                            <p class="font-medium">{{ $courrier->expediteur }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Objet</p>
                            <p class="font-medium">{{ $courrier->objet }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Type</p>
                            <p>
                                @if($courrier->type == 'urgent')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Urgent
                                    </span>
                                @elseif($courrier->type == 'confidentiel')
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
                            <p class="font-medium">{{ $courrier->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">Destinataires</h3>
                <div class="mt-2 bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 gap-2">
                        <div>
                            <p class="text-xs text-gray-500">Destinataire principal</p>
                            <p class="font-medium">{{ $courrier->destinataireInterne->name }} ({{ $courrier->destinataireInterne->service }})</p>
                        </div>
                        
                        @if($courrier->destinataires->count() > 0)
                            <div>
                                <p class="text-xs text-gray-500">Destinataires en copie</p>
                                <div class="mt-1 flex flex-wrap gap-1">
                                    @foreach($courrier->destinataires as $destinataire)
                                        <span class="px-2 py-1 text-xs bg-gray-200 rounded-full">
                                            {{ $destinataire->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500">Statut et suivi</h3>
                <div class="mt-2 bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 gap-2">
                        <div>
                            <p class="text-xs text-gray-500">Statut</p>
                            <p>
                                @if($courrier->statut == 'en_cours')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        En cours
                                    </span>
                                @elseif($courrier->statut == 'traite')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Traité
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Archivé
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Nom du déchargeur</p>
                            <p class="font-medium">{{ $courrier->nom_dechargeur }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Nombre d'annotations</p>
                            <p class="font-medium">{{ $courrier->annotations->count() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Partagé avec</p>
                            @if($courrier->shares->count() > 0)
                                <div class="mt-1 flex flex-wrap gap-1">
                                    @foreach($courrier->shares as $share)
                                        <span class="px-2 py-1 text-xs rounded-full {{ $share->is_read ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $share->recipient->name }}
                                            @if($share->is_read)
                                                <span class="ml-1">(Lu)</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-400">Aucun partage</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document joint avec visualisation intégrée -->
    @if($courrier->document_path)
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Document joint</h2>
            
            @php
                $extension = pathinfo($courrier->document_path, PATHINFO_EXTENSION);
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
                        <p class="text-sm font-medium">{{ basename($courrier->document_path) }}</p>
                        <div class="flex space-x-3 mt-1">
                            <a href="{{ route('document.view', $courrier->document_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Ouvrir dans un nouvel onglet
                            </a>
                            
                            <a href="{{ route('document.download', $courrier->document_path) }}" class="text-green-600 hover:text-green-800 text-sm flex items-center">
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
                            <iframe src="{{ route('document.view', $courrier->document_path) }}" class="w-full h-full" frameborder="0"></iframe>
                        </div>
                    @elseif($isImage)
                        <div class="flex justify-center p-4 bg-gray-100">
                            <img src="{{ route('document.view', $courrier->document_path) }}" alt="Document" class="max-w-full max-h-96 object-contain">
                        </div>
                    @else
                        <div class="p-6 bg-gray-100 text-center">
                            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-lg font-medium text-gray-700">Aperçu non disponible</p>
                            <p class="text-sm text-gray-500 mt-1">Le type de document ne peut pas être affiché directement.</p>
                            <a href="{{ route('document.view', $courrier->document_path) }}" target="_blank" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Ouvrir le document
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Courriers sortants liés (réponses) -->
    @if($courrier->courriersSortants->count() > 0 && Auth::user()->canManageCourriers())
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Courriers sortants liés</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinataire</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objet</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Décharge</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($courrier->courriersSortants as $courrierSortant)
                            <tr class="hover:bg-gray-50 {{ $courrierSortant->decharge_manquante ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $courrierSortant->numero }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $courrierSortant->date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $courrierSortant->destinataire }}</td>
                                <td class="px-6 py-4">{{ $courrierSortant->objet }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($courrierSortant->decharge)
                                        <a href="{{ route('document.view', $courrierSortant->decharge) }}" target="_blank" class="text-green-600 hover:text-green-800 flex items-center">
                                            <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Voir
                                        </a>
                                    @else
                                        <span class="text-red-500">Manquante</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('courriers-sortants.show', $courrierSortant) }}" class="text-blue-600 hover:text-blue-900 mr-2">
                                        <svg class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Annotations -->
    @if($courrier->annotations->count() > 0 && (Auth::user()->canAnnotateCourriers() || $courrier->isSharedWith(Auth::user())))
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Annotations</h2>
            
            <div class="space-y-4">
                @foreach($courrier->annotations as $annotation)
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="font-medium">{{ $annotation->annotator->name }}</span>
                                <span class="text-sm text-gray-500 ml-2">{{ $annotation->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if(Auth::id() === $annotation->annotated_by || Auth::user()->isAdmin())
                                <form action="{{ route('courriers.annotations.destroy', $annotation) }}" method="POST" class="ml-2" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annotation?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="whitespace-pre-line text-gray-700">{{ $annotation->annotation }}</div>
                        
                        @if($annotation->shares->count() > 0 && Auth::user()->canAnnotateCourriers())
                            <div class="mt-2 pt-2 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-500">Partagé avec:</p>
                                <div class="flex flex-wrap mt-1 gap-1">
                                    @foreach($annotation->shares as $share)
                                        <span class="px-2 py-1 text-xs rounded-full {{ $share->is_read ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $share->recipient->name }}
                                            @if($share->is_read)
                                                <span class="ml-1">(Lu)</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @elseif(Auth::user()->canAnnotateCourriers())
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900">Aucune annotation</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Ce courrier n'a pas encore été annoté.
                </p>
                <div class="mt-4">
                    <a href="{{ route('courriers.annotations.create', $courrier) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150">
                        Ajouter une annotation
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Suppression du courrier (pour les administrateurs et gestionnaires) -->
    @if(Auth::user()->hasRole(['admin', 'gestionnaire']))
        <div class="mt-8 border-t pt-6">
            <div class="flex justify-end">
                <form action="{{ route('courriers.destroy', $courrier) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce courrier? Cette action est irréversible.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Supprimer ce courrier
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection