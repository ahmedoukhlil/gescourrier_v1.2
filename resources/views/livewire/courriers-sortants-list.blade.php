<!-- resources/views/livewire/courriers-sortants-list.blade.php -->
<div>
    <div class="mb-4 bg-white p-4 rounded shadow">
        <div class="flex flex-wrap items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Filtres</h3>
            <button wire:click="resetFilters" class="px-3 py-1 text-sm text-white bg-gray-500 rounded hover:bg-gray-600">
                Réinitialiser
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Recherche</label>
                <input wire:model.debounce.300ms="search" type="text" id="search" placeholder="Numéro, destinataire, objet..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            
            <div>
                <label for="filter" class="block text-sm font-medium text-gray-700">Statut de décharge</label>
                <select wire:model="filter" id="filter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Tous</option>
                    <option value="with-decharge">Avec décharge</option>
                    <option value="without-decharge">Sans décharge</option>
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

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinataire</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objet</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courrier Entrant</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Décharge</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($courriersSortants as $courrierSortant)
                    <tr class="hover:bg-gray-50 @if($courrierSortant->decharge_manquante) bg-red-50 @endif">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $courrierSortant->numero }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($courrierSortant->date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $courrierSortant->destinataire }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $courrierSortant->objet }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($courrierSortant->courrierEntrant)
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                    {{ $courrierSortant->courrierEntrant->numero_ordre }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($courrierSortant->decharge)
                                <a href="{{ route('document.view', $courrierSortant->decharge) }}" target="_blank" class="text-green-600 hover:text-green-800 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Voir
                                </a>
                            @else
                                <button
                                    type="button"
                                    wire:click="openDechargeModal({{ $courrierSortant->id }})"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Ajouter
                                </button>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('courriers-sortants.show', $courrierSortant->id) }}" class="text-blue-600 hover:text-blue-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('courriers-sortants.edit', $courrierSortant->id) }}" class="text-green-600 hover:text-green-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button wire:click="deleteConfirm({{ $courrierSortant->id }})" class="text-red-600 hover:text-red-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucun courrier sortant trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $courriersSortants->links() }}
    </div>
</div>