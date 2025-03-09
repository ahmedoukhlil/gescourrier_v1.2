<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistiques principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Carte pour les courriers entrants -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <div class="text-sm font-medium text-gray-500">Total Courriers Entrants</div>
                                <div class="text-3xl font-bold text-gray-800">{{ $stats['total_courriers_entrants'] }}</div>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('courriers.index') }}" class="text-sm text-blue-500 hover:underline">Voir tous les courriers →</a>
                        </div>
                    </div>
                </div>

                <!-- Carte pour les courriers sortants -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <div class="text-sm font-medium text-gray-500">Total Courriers Sortants</div>
                                <div class="text-3xl font-bold text-gray-800">{{ $stats['total_courriers_sortants'] }}</div>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('courriers-sortants.index') }}" class="text-sm text-green-500 hover:underline">Voir tous les courriers sortants →</a>
                        </div>
                    </div>
                </div>

                <!-- Carte pour les courriers urgents -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <div class="text-sm font-medium text-gray-500">Courriers Urgents</div>
                                <div class="text-3xl font-bold text-red-600">{{ $stats['courriers_urgent'] }}</div>
                            </div>
                            <div class="p-3 rounded-full bg-red-100 text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('courriers.index', ['type' => 'urgent']) }}" class="text-sm text-red-500 hover:underline">Voir les courriers urgents →</a>
                        </div>
                    </div>
                </div>

                <!-- Carte dynamique basée sur le rôle -->
                @if(Auth::user()->isLecteur())
                    <!-- Carte pour les courriers partagés non lus (Lecteurs) -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-500">Courriers Partagés Non Lus</div>
                                    <div class="text-3xl font-bold text-indigo-600">{{ $stats['unread_shares'] }}</div>
                                </div>
                                <div class="p-3 rounded-full bg-indigo-100 text-indigo-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('courriers.shared') }}" class="text-sm text-indigo-500 hover:underline">Voir les courriers partagés →</a>
                            </div>
                        </div>
                    </div>
                @elseif(Auth::user()->canAnnotateCourriers())
                    <!-- Carte pour les projets en attente d'examen (Gestionnaires) -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-500">Projets en attente d'examen</div>
                                    <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending_drafts_review'] }}</div>
                                </div>
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#projets-examination" class="text-sm text-yellow-500 hover:underline">Voir les projets à examiner →</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Section d'activité récente -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Activité Récente</h3>
                    
                    <div class="border-t border-gray-200">
                        @forelse($stats['recent_courriers'] as $courrier)
                            <div class="py-3 flex items-center justify-between border-b border-gray-100">
                                <div>
                                    <span class="font-medium">Courrier #{{ $courrier->numero_ordre }}</span>
                                    <p class="text-sm text-gray-500">{{ Str::limit($courrier->objet, 50) }}</p>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-xs text-gray-500 mr-3">{{ $courrier->created_at->diffForHumans() }}</span>
                                    @if($courrier->type == 'urgent')
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Urgent</span>
                                    @elseif($courrier->type == 'confidentiel')
                                        <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Confidentiel</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Normal</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="py-4 text-gray-500 italic">Aucune activité récente à afficher.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Section spécifique au rôle utilisateur -->
            @if(Auth::user()->canAnnotateCourriers())
                <!-- Section pour les gestionnaires : projets à examiner -->
                <div id="projets-examination" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Projets en attente d'examen</h3>
                        
                        @if(isset($stats['pending_drafts_review']) && $stats['pending_drafts_review'] > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courrier</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Soumis par</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Cette partie devrait être remplie par les données réelles -->
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                #12345
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                John Doe
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                Il y a 2 jours
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Examiner</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="py-4 text-gray-500 italic">Aucun projet en attente d'examen.</p>
                        @endif
                    </div>
                </div>
            @elseif(Auth::user()->isLecteur())
                <!-- Section pour les lecteurs : mes projets de réponse -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Mes Projets de Réponse</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="border rounded-lg p-4 bg-yellow-50">
                                <h4 class="font-medium text-yellow-800">En attente d'examen</h4>
                                <div class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending_drafts'] }}</div>
                                <p class="text-sm text-yellow-600 mt-1">Projets en attente de retour</p>
                            </div>
                            
                            <div class="border rounded-lg p-4 bg-green-50">
                                <h4 class="font-medium text-green-800">Examinés</h4>
                                <div class="text-3xl font-bold text-green-600 mt-2">{{ $stats['reviewed_drafts'] }}</div>
                                <p class="text-sm text-green-600 mt-1">Projets avec feedback</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Cartes de raccourcis -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Carte Courriers Entrants -->
                <div class="bg-blue-50 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <h4 class="text-lg font-semibold text-blue-800">Courriers Entrants</h4>
                    </div>
                    <p class="text-gray-600 mb-4">Gérez les courriers entrants, assignez des destinataires et suivez leur statut.</p>
                    <a href="{{ route('courriers.index') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                        Accéder
                    </a>
                </div>

                <!-- Carte Courriers Sortants -->
                <div class="bg-green-50 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <h4 class="text-lg font-semibold text-green-800">Courriers Sortants</h4>
                    </div>
                    <p class="text-gray-600 mb-4">Créez et gérez les courriers sortants, téléchargez les décharges signées.</p>
                    <a href="{{ route('courriers-sortants.index') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">
                        Accéder
                    </a>
                </div>

                <!-- Carte Courriers Partagés -->
                <div class="bg-indigo-50 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        <h4 class="text-lg font-semibold text-indigo-800">Courriers Partagés</h4>
                    </div>
                    <p class="text-gray-600 mb-4">Consultez les courriers qui ont été partagés avec vous par les gestionnaires.</p>
                    <a href="{{ route('courriers.shared') }}" class="inline-block bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded">
                        Accéder
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>