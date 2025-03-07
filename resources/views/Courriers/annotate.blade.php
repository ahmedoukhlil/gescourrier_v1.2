@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Annoter le courrier #{{ $courrier->numero_ordre }}</h1>
        <a href="{{ route('courriers.show', $courrier) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
            Retour au courrier
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Informations du courrier</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Expéditeur</p>
                <p class="mt-1">{{ $courrier->expediteur }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Numéro d'ordre</p>
                <p class="mt-1">{{ $courrier->numero_ordre }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Objet</p>
                <p class="mt-1">{{ $courrier->objet }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Type</p>
                <p class="mt-1">
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
                <p class="text-sm font-medium text-gray-500">Destinataire principal</p>
                <p class="mt-1">{{ $courrier->destinataireInterne->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Statut</p>
                <p class="mt-1">
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
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Formulaire d'annotation et partage -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Ajouter une annotation et partager</h2>
            
            <form action="{{ route('courriers.annotations.store', $courrier) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="annotation" class="block text-sm font-medium text-gray-700 mb-1">Annotation</label>
                    <textarea 
                        name="annotation" 
                        id="annotation" 
                        rows="6" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        required
                    >{{ old('annotation') }}</textarea>
                    @error('annotation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Partager avec (lecteurs uniquement)</label>
                    <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                        @forelse($lecteurs as $lecteur)
                            <div class="flex items-center mb-2">
                                <input 
                                    type="checkbox" 
                                    name="share_with[]" 
                                    id="user-{{ $lecteur->id }}" 
                                    value="{{ $lecteur->id }}"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    {{ in_array($lecteur->id, old('share_with', [])) ? 'checked' : '' }}
                                >
                                <label for="user-{{ $lecteur->id }}" class="ml-2 text-sm text-gray-700">
                                    {{ $lecteur->name }} ({{ $lecteur->service }})
                                </label>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 italic">Aucun utilisateur avec le rôle lecteur n'est disponible.</p>
                        @endforelse
                    </div>
                    @error('share_with')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Enregistrer et partager
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Liste des annotations existantes -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Annotations précédentes</h2>
            
            @if($annotations->count() > 0)
                <div class="space-y-4">
                    @foreach($annotations as $annotation)
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
                            
                            @if($annotation->shares->count() > 0)
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
            @else
                <p class="text-gray-500 italic">Aucune annotation n'a encore été ajoutée à ce courrier.</p>
            @endif
        </div>
    </div>
    
    <!-- Liste des partages existants -->
    <div class="mt-6 bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Partages actifs</h2>
        
        @if($shares->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partagé avec</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de partage</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Annotation</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($shares as $share)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $share->recipient->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $share->recipient->service }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $share->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($share->is_read)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Lu</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Non lu</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($share->annotation)
                                        <span class="truncate block max-w-xs">{{ Str::limit($share->annotation->annotation, 50) }}</span>
                                    @else
                                        <span class="text-gray-400">Aucune annotation</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('courriers.shares.destroy', $share) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce partage?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 italic">Ce courrier n'a pas encore été partagé avec d'autres utilisateurs.</p>
        @endif
    </div>
</div>
@endsection