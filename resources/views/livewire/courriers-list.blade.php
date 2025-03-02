<div>
    <div class="mb-4 bg-white p-4 rounded shadow">
        <div class="flex flex-wrap items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Filtres</h3>
            <button wire:click="resetFilters" class="px-3 py-1 text-sm text-white bg-gray-500 rounded hover:bg-gray-600">
                Réinitialiser
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Recherche</label>
                <input wire:model.debounce.300ms="search" type="text" id="search" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
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

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Ordre</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expéditeur</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objet</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinataire</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                                <strong>{{ optional($courrier->destinataireInterne)->nom }}</strong>
                            </div>
                            @if($courrier->destinataires->count() > 0)
                                <div class="text-xs text-gray-400 mt-1">
                                    CC: {{ $courrier->destinataires->pluck('nom')->implode(', ') }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($courrier->statut)
                                @case('en_cours')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        En cours
                                    </span>
                                    @break
                                @case('traite')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Traité
                                    </span>
                                    @break
                                @default
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Archivé
                                    </span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $courrier->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($courrier->document_path)
                                @php
                                    $fullPath = Storage::url($courrier->document_path);
                                    $extension = pathinfo($courrier->document_path, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                
                                <a href="{{ route('document.view', $courrier->document_path) }}" target="_blank" class="flex items-center text-blue-600 hover:underline">
                                    @if($isImage)
                                        <img src="{{ Storage::url($courrier->document_path) }}" alt="Aperçu" class="h-10 w-10 object-cover mr-2 border rounded">
                                    @endif
                                    Voir
                                </a>
                            @else
                                <span class="text-gray-400">Aucun</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                    
                                </a>
                                    
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

    <div class="mt-4">
        {{ $courriers->links() }}
    </div>
</div>