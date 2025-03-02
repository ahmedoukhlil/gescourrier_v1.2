<div>
    <!-- Bouton pour ouvrir le modal -->
    <button 
        wire:click="openModal" 
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
    >
        <span class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nouveau Courrier
        </span>
    </button>

    <!-- Modal -->
    @if($isOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="relative bg-white rounded-lg max-w-3xl w-full max-h-screen overflow-y-auto">
                <!-- Header -->
                <div class="flex justify-between items-center p-5 border-b">
                    <h3 class="text-xl font-medium">Nouveau Courrier</h3>
                    <button 
                        wire:click="closeModal" 
                        class="text-gray-500 hover:text-gray-700 focus:outline-none"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="p-5">
                    <form wire:submit.prevent="save" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="expediteur" class="block text-sm font-medium text-gray-700">Expéditeur</label>
                                <input wire:model.defer="expediteur" type="text" id="expediteur" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('expediteur') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                <select wire:model.defer="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="normal">Normal</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="confidentiel">Confidentiel</option>
                                </select>
                                @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="objet" class="block text-sm font-medium text-gray-700">Objet</label>
                            <input wire:model.defer="objet" type="text" id="objet" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('objet') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="destinataire_id" class="block text-sm font-medium text-gray-700">Destinataire Principal</label>
                            <select wire:model.defer="destinataire_id" id="destinataire_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Sélectionner un destinataire</option>
                                @foreach($destinataires as $destinataire)
                                    <option value="{{ $destinataire->id }}">{{ $destinataire->nom }} ({{ $destinataire->service }})</option>
                                @endforeach
                            </select>
                            @error('destinataire_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="additional_destinataires" class="block text-sm font-medium text-gray-700">Destinataires en copie (CC)</label>
                            <select wire:model.defer="additional_destinataires" id="additional_destinataires" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" multiple>
                                @foreach($destinataires as $destinataire)
                                    <option value="{{ $destinataire->id }}">{{ $destinataire->nom }} ({{ $destinataire->service }})</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs destinataires.</p>
                            @error('additional_destinataires') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="nom_dechargeur" class="block text-sm font-medium text-gray-700">Nom du Déchargeur</label>
                            <input wire:model.defer="nom_dechargeur" type="text" id="nom_dechargeur" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('nom_dechargeur') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="document" class="block text-sm font-medium text-gray-700">Document (PDF, Word, etc.)</label>
                            <input wire:model="document" type="file" id="document" class="mt-1 block w-full">
                            <div wire:loading wire:target="document" class="text-sm text-gray-500 mt-1">
                                Chargement...
                            </div>
                            @error('document') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>
                
                <!-- Footer -->
                <div class="px-5 py-4 border-t flex justify-end space-x-3">
                    <button 
                        wire:click="closeModal" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400"
                    >
                        Annuler
                    </button>
                    <button 
                        wire:click="save" 
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