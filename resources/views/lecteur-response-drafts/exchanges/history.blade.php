<!-- resources/views/lecteur-response-drafts/exchanges/history.blade.php -->

<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h2 class="text-lg font-semibold mb-4">Historique des échanges ({{ $exchanges->count() }})</h2>
    
    @if($exchanges->count() > 0)
        <div class="space-y-6">
            @foreach($exchanges as $exchange)
                <div class="border-l-4 {{ $exchange->type == 'feedback' ? 'border-indigo-500 bg-indigo-50' : 'border-green-500 bg-green-50' }} p-4 rounded-r-lg">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="font-medium">{{ $exchange->user->name }}</span>
                            <span class="text-sm text-gray-500 ml-2">{{ $exchange->created_at->format('d/m/Y H:i') }}</span>
                            <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $exchange->type == 'feedback' ? 'bg-indigo-200 text-indigo-800' : 'bg-green-200 text-green-800' }}">
                                {{ $exchange->type == 'feedback' ? 'Commentaire' : 'Révision' }}
                            </span>
                        </div>
                    </div>
                    <div class="whitespace-pre-line text-gray-700 mb-3">{{ $exchange->comment }}</div>
                    
                    @if($exchange->file_path)
                        <div class="flex items-center mt-2 p-2 bg-white rounded border border-gray-200">
                            <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium">Document révisé</p>
                                <div class="flex space-x-3 mt-1">
                                    <a href="{{ route('document.view', $exchange->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Voir
                                    </a>
                                    
                                    <a href="{{ route('document.download', $exchange->file_path) }}" class="text-green-600 hover:text-green-800 text-sm flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Télécharger
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900">Aucun échange</h3>
            <p class="mt-1 text-sm text-gray-500">
                Il n'y a pas encore eu d'échanges sur ce projet de réponse.
            </p>
        </div>
    @endif
    
    <div class="mt-6 flex justify-center">
        @if(Auth::user()->canAnnotateCourriers() && !$draft->is_reviewed)
            <a href="{{ route('response-draft-exchanges.create', $draft) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                Ajouter un commentaire
            </a>
        @elseif(Auth::id() === $draft->user_id && $draft->needs_revision)
            <a href="{{ route('response-draft-exchanges.create', $draft) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Soumettre une révision
            </a>
        @endif
    </div>
</div>