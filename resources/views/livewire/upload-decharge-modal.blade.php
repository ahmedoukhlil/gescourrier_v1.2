<div>
    <!-- Le modal s'ouvre via la méthode openModal($courrierSortantId) -->
    @if($isOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="relative bg-white rounded-lg max-w-lg w-full">
                <!-- Header -->
                <div class="flex justify-between items-center p-5 border-b">
                    <h3 class="text-xl font-medium">Ajouter une décharge</h3>
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
                        @if($courrierSortant)
                        <div class="bg-gray-50 p-3 rounded-md">
                            <p><strong>Courrier :</strong> {{ $courrierSortant->numero }}</p>
                            <p><strong>Destinataire :</strong> {{ $courrierSortant->destinataire }}</p>
                            <p><strong>Objet :</strong> {{ $courrierSortant->objet }}</p>
                            <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($courrierSortant->date)->format('d/m/Y') }}</p>
                        </div>
                        @endif

                        <div>
                            <label for="decharge" class="block text-sm font-medium text-gray-700">
                                Document de décharge (PDF, image)
                            </label>
                            <div class="mt-1">
                                <input 
                                    wire:model="decharge" 
                                    type="file" 
                                    id="decharge" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                >
                            </div>
                            <div wire:loading wire:target="decharge" class="text-sm text-gray-500 mt-1">
                                Chargement...
                            </div>
                            @error('decharge') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-4 text-sm text-gray-600">
                            <p class="font-medium">Note :</p>
                            <p>Veuillez scanner la décharge signée par le destinataire et la télécharger ici. La décharge sera associée au courrier sortant et marquée comme reçue dans le système.</p>
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
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
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