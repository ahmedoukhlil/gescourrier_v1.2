<div>
    <!-- Bouton pour ouvrir le modal -->
    <button 
        wire:click="openModal" 
        type="button"
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
    >
        <span class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nouveau Courrier Départ
        </span>
    </button>

    <!-- Modal -->
    @if($isOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="relative bg-white rounded-lg max-w-3xl w-full max-h-screen overflow-y-auto">
                <!-- Header -->
                <div class="flex justify-between items-center p-5 border-b">
                    <h3 class="text-xl font-medium">Nouveau Courrier Sortant</h3>
                    <button 
                        wire:click="closeModal" 
                        type="button"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="p-5">
                    <div class="space-y-4">
                        <div>
                            <label for="courrier_entrant_id" class="block text-sm font-medium text-gray-700">Courrier Entrant (Optionnel - si c'est une réponse)</label>
                            <select 
                                wire:model="courrier_entrant_id" 
                                id="courrier_entrant_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            >
                                <option value="">-- Aucun --</option>
                                @foreach($courriersEntrants as $courrierEntrant)
                                    <option value="{{ $courrierEntrant->id }}">
                                        {{ $courrierEntrant->numero_ordre }} - {{ $courrierEntrant->objet }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="objet" class="block text-sm font-medium text-gray-700">Objet</label>
                            <input 
                                wire:model="objet" 
                                type="text" 
                                id="objet" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                required
                            >
                            @error('objet') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

<div class="relative">
    <label for="destinataireSearch" class="block text-sm font-medium text-gray-700">Destinataire</label>
    <input 
        wire:model.debounce.300ms="destinataireSearch" 
        type="text" 
        id="destinataireSearch" 
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        autocomplete="off"
        placeholder="Commencez à saisir le nom du destinataire..."
        required
    >
    @error('destinataire') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    
    <!-- Dropdown pour l'autocomplétion -->
    @if($showDestinataireDropdown && count($destinatairesResults) > 0)
    <div class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-300 max-h-60 overflow-y-auto">
        <ul class="py-1">
            @foreach($destinatairesResults as $result)
            <li>
                <button
                    type="button"
                    wire:click="selectDestinataire({{ $result->id }})"
                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 focus:bg-gray-100"
                >
                    <span class="font-medium">{{ $result->nom }}</span>
                    @if($result->organisation)
                    <span class="text-gray-500 ml-2">({{ $result->organisation }})</span>
                    @endif
                </button>
            </li>
            @endforeach
        </ul>
    </div>
    @elseif($showDestinataireDropdown && $destinataireSearch && count($destinatairesResults) === 0)
    <div class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-300">
        <div class="px-4 py-2 text-sm text-gray-700">
            Aucun résultat trouvé. Le destinataire sera créé automatiquement.
        </div>
    </div>
    @endif
</div>
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input 
                                wire:model="date" 
                                type="date" 
                                id="date" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                required
                            >
                            @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-4 text-sm text-gray-600">
                            <p class="font-medium">Note :</p>
                            <p>Après l'enregistrement, le courrier sortant sera généré avec un numéro automatique. Vous pourrez ensuite ajouter la décharge une fois que le courrier aura été distribué et signé par le destinataire.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="px-5 py-4 border-t flex justify-end space-x-3">
                    <button 
                        wire:click="closeModal" 
                        type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400"
                    >
                        Annuler
                    </button>
                    <button 
                        wire:click="save" 
                        type="button"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="save">Enregistrer</span>
                        <span wire:loading wire:target="save">Traitement...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>