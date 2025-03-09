<div class="relative" 
     x-data="{ isActive: false }" 
     @click.away="isActive = false; @this.hideResults()">
    
    <div class="flex items-center">
        <div class="relative w-64">
            <input 
                wire:model.debounce.300ms="searchTerm" 
                type="text" 
                class="w-full h-10 pl-10 pr-4 py-1 text-base placeholder-gray-500 border border-gray-200 rounded-full focus:outline-none focus:ring-1 focus:ring-blue-500" 
                placeholder="Rechercher un courrier..." 
                @focus="isActive = true"
            >
            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            @if($searchTerm)
                <button 
                    wire:click="$set('searchTerm', '')" 
                    class="absolute inset-y-0 right-0 flex items-center pr-3"
                >
                    <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>
        <div class="ml-2">
            <a href="{{ route('courriers.index') }}" class="inline-flex items-center text-sm px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full">
                <span>Recherche avancée</span>
                <svg class="ml-1 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>
    
    <!-- Résultats de la recherche -->
    @if($showResults && count($searchResults) > 0)
        <div 
            class="absolute z-50 w-full mt-2 bg-white border border-gray-300 rounded-md shadow-lg" 
            x-show="isActive"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <ul class="py-1">
                @foreach($searchResults as $result)
                    <li>
                        <a 
                            href="{{ route('courriers.show', $result) }}" 
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            @click="isActive = false"
                        >
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-semibold text-gray-800">{{ $result->numero_ordre }}</span>
                                    <p class="text-xs text-gray-500 truncate">{{ Str::limit($result->objet, 40) }}</p>
                                </div>
                                <div>
                                    @if($result->type == 'urgent')
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Urgent</span>
                                    @elseif($result->type == 'confidentiel')
                                        <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Confid.</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Normal</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="px-4 py-2 border-t">
                <a href="{{ route('courriers.index', ['search' => $searchTerm]) }}" class="text-sm text-blue-500 hover:underline">
                    Voir tous les résultats
                </a>
            </div>
        </div>
    @elseif($showResults && count($searchResults) == 0 && strlen($searchTerm) >= 3)
        <div 
            class="absolute z-50 w-full mt-2 bg-white border border-gray-300 rounded-md shadow-lg p-4 text-center" 
            x-show="isActive"
        >
            <p class="text-gray-500">Aucun résultat trouvé pour "{{ $searchTerm }}"</p>
        </div>
    @endif
</div>