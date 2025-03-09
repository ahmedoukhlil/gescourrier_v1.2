<div>
    <!-- En-tête avec filtres -->
    <div class="mb-4 bg-white p-4 rounded shadow">
        <div class="flex flex-wrap items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Filtres</h3>
            <div class="flex space-x-2">
                <button 
                    wire:click="resetFilters" 
                    class="px-3 py-1 text-sm text-white bg-gray-500 rounded hover:bg-gray-600"
                >
                    Réinitialiser
                </button>
                
                <!-- Sélecteur de vue -->
                <div class="border rounded-md overflow-hidden flex">
                    <button 
                        wire:click="toggleViewMode('table')" 
                        class="px-3 py-1 text-sm {{ $viewMode === 'table' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                    <button 
                        wire:click="toggleViewMode('cards')" 
                        class="px-3 py-1 text-sm {{ $viewMode === 'cards' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Recherche</label>
                <input wire:model.debounce.300ms="search" type="text" id="search" placeholder="Numéro, destinataire, objet..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select wire:model="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Tous</option>
                    <option value="en_cours">En cours</option>
                    <option value="traite">Traité</option>
                    <option value="archive">Archivé</option>
                </select>
            </div>
            
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <select wire:model="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Tous</option>
                    <option value="normal">Normal</option>
                    <option value="urgent">Urgent</option>
                    <option value="confidentiel">Confidentiel</option>
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label for="dateFrom" class="block text-sm font-medium text-gray-700">Date de début</label>
                    <input wire:model="dateFrom" type="date" id="dateFrom" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                
                <div>
                    <label for="dateTo" class="block text-sm font-medium text-gray-700">Date de fin</label>
                    <input wire:model="dateTo" type="date" id="dateTo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>
        </div>
    </div>

    <!-- Affichage en mode tableau -->
    @if($viewMode === 'table')
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('numero_ordre')">
                            <div class="flex items-center">
                                N° Ordre
                                @if($sortField === 'numero_ordre')
                                    <svg class="ml-1 h-4 w-4 {{ $sortDirection === 'asc' ? '' : 'transform rotate-180' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('expediteur')">
                            <div class="flex items-center">
                                Expéditeur
                                @if($sortField === 'expediteur')
                                    <svg class="ml-1 h-4 w-4 {{ $sortDirection === 'asc' ? '' : 'transform rotate-180' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('type')">
                            <div class="flex items-center">
                                Type
                                @if($sortField === 'type')
                                    <svg class="ml-1 h-4 w-4 {{ $sortDirection === 'asc' ? '' : 'transform rotate-180' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('objet')">
                            <div class="flex items-center">
                                Objet
                                @if($sortField === 'objet')
                                    <svg class="ml-1 h-4 w-4 {{ $sortDirection === 'asc' ? '' : 'transform rotate-180' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Destinataire
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('statut')">
                            <div class="flex items-center">
                                Statut
                                @if($sortField === 'statut')
                                    <svg class="ml-1 h-4 w-4 {{ $sortDirection === 'asc' ? '' : 'transform rotate-180' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                            <div class="flex items-center">
                                Date
                                @if($sortField === 'created_at')
                                    <svg class="ml-1 h-4 w-4 {{ $sortDirection === 'asc' ? '' : 'transform rotate-180' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Document
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($courriers as $courrier)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $courrier->numero_ordre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $courrier->expediteur }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @switch($courrier->type)
                                    @case('urgent')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Urgent
                                        </span>
                                        @break
                                    @case('confidentiel')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Confidentiel
                                        </span>
                                        @break
                                    @default
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Normal
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $courrier->objet }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div>
                                    <strong>{{ $courrier->destinataireInterne->name }}</strong>
                                </div>
                                @if($courrier->destinataires->count() > 0)
                                    <div class="text-xs text-gray-400 mt-1">
                                        CC: {{ $courrier->destinataires->pluck('name')->implode(', ') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $hasAnnotations = $courrier->annotations->count() > 0;
                                    $hasResponseDrafts = $courrier->responseDrafts->count() > 0;
                                    $hasPendingDrafts = $courrier->responseDrafts->where('is_reviewed', false)->count() > 0;
                                    $allDraftsReviewed = $hasResponseDrafts && !$hasPendingDrafts;
                                @endphp

                                @if($hasPendingDrafts)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Projet en attente
                                    </span>
                                @elseif($allDraftsReviewed)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Projets validés
                                    </span>
                                @elseif($hasAnnotations)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Annoté
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Validé
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $courrier->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($courrier->document_path)
                                    <button wire:click="viewDocument('{{ $courrier->document_path }}')" class="flex items-center text-blue-600 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Voir
                                    </button>
                                @else
                                    <span class="text-gray-400">Aucun</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('courriers.show', $courrier) }}" class="text-blue-600 hover:text-blue-900" title="Afficher">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('courriers.edit', $courrier) }}" class="text-green-600 hover:text-green-900" title="Modifier">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button wire:click="deleteConfirm({{ $courrier->id }})" class="text-red-600 hover:text-red-900" title="Supprimer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                Aucun courrier trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <!-- Affichage en mode cartes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($courriers as $courrier)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <div class="p-4 border-b">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $courrier->numero_ordre }}</h3>
                                <p class="text-sm text-gray-500">{{ $courrier->expediteur }}</p>
                            </div>
                            @switch($courrier->type)
                                @case('urgent')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Urgent
                                    </span>
                                    @break
                                @case('confidentiel')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Confidentiel
                                    </span>
                                    @break
                                @default
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Normal
                                    </span>
                            @endswitch
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-500">Objet</h4>
                            <p class="mt-1">{{ $courrier->objet }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-500">Destinataire</h4>
                            <p class="mt-1">{{ $courrier->destinataireInterne->name }}</p>
                            @if($courrier->destinataires->count() > 0)
                                <p class="text-xs text-gray-400 mt-1">
                                    CC: {{ $courrier->destinataires->pluck('name')->take(2)->implode(', ') }}
                                    @if($courrier->destinataires->count() > 2)
                                        + {{ $courrier->destinataires->count() - 2 }} autres
                                    @endif
                                </p>
                            @endif
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-400">{{ $courrier->created_at->format('d/m/Y') }}</p>
                            </div>
                            
                            @php
                                $hasAnnotations = $courrier->annotations->count() > 0;
                                $hasResponseDrafts = $courrier->responseDrafts->count() > 0;
                                $hasPendingDrafts = $courrier->responseDrafts->where('is_reviewed', false)->count() > 0;
                                $allDraftsReviewed = $hasResponseDrafts && !$hasPendingDrafts;
                            @endphp

                            @if($hasPendingDrafts)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Projet en attente
                                </span>
                            @elseif($allDraftsReviewed)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    Projets validés
                                </span>
                            @elseif($hasAnnotations)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Annoté
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Validé
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-4 bg-gray-50 border-t">
                        <div class="flex justify-between">
                            <div>
                                @if($courrier->document_path)
                                    <button wire:click="viewDocument('{{ $courrier->document_path }}')" class="inline-flex items-center text-sm text-blue-600 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Document
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400">Aucun document</span>
                                @endif
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="{{ route('courriers.show', $courrier) }}" class="text-blue-600 hover:text-blue-900" title="Afficher">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('courriers.edit', $courrier) }}" class="text-green-600 hover:text-green-900" title="Modifier">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button wire:click="deleteConfirm({{ $courrier->id }})" class="text-red-600 hover:text-red-900" title="Supprimer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun courrier trouvé</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Essayez de modifier vos critères de recherche.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    @endif

    <!-- Pagination -->
    <div class="mt-4">
        {{ $courriers->links() }}
    </div>

    <!-- Modal pour l'aperçu des documents -->
    @if($showDocumentModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                
                <!-- Centre le modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <!-- Modal content -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Aperçu du document
                                </h3>
                                <div class="mt-4">
                                    @php
                                        $extension = pathinfo($selectedDocument, PATHINFO_EXTENSION);
                                        $isPdf = strtolower($extension) === 'pdf';
                                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    
                                    @if($isPdf)
                                        <div style="height: 70vh;">
                                            <iframe src="{{ route('document.view', $selectedDocument) }}" class="w-full h-full" frameborder="0"></iframe>
                                        </div>
                                    @elseif($isImage)
                                        <div class="flex justify-center">
                                            <img src="{{ route('document.view', $selectedDocument) }}" alt="Document" class="max-h-[70vh] object-contain">
                                        </div>
                                    @else
                                        <div class="text-center py-8">
                                            <p class="text-gray-500">
                                                Ce type de document ne peut pas être affiché directement.
                                                <a href="{{ route('document.view', $selectedDocument) }}" target="_blank" class="text-blue-500 underline">
                                                    Cliquez ici pour ouvrir le document
                                                </a>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <a href="{{ route('document.download', $selectedDocument) }}" target="_blank" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Télécharger
                        </a>
                        <button type="button" wire:click="closeDocumentModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div> 