<div
    x-data="{
        init() {
            // Écouter l'événement pour récupérer le timestamp de dernière connexion
            window.addEventListener('get-last-login', () => {
                const lastLogin = localStorage.getItem('last_login_timestamp');
                if (lastLogin) {
                    $wire.checkNewActivities(lastLogin);
                }
            });
            
            // Écouter l'événement pour stocker le timestamp de dernière connexion
            window.addEventListener('set-last-login', (event) => {
                localStorage.setItem('last_login_timestamp', event.detail.timestamp);
            });
        }
    }"
    class="relative z-50"
>
    <!-- Notification de nouvelles activités -->
    @if($showNotifications && count($newActivities) > 0)
        <div 
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div 
                class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 overflow-hidden"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
            >
                <!-- En-tête -->
                <div class="bg-indigo-600 px-4 py-3 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-white">
                        Nouvelles activités
                    </h3>
                    <button 
                        wire:click="dismissNotifications"
                        class="text-white hover:text-indigo-100 focus:outline-none"
                    >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Corps -->
                <div class="px-4 py-3">
                    <p class="text-gray-700 mb-4">
                        Vous avez {{ $this->newActivitiesCount }} nouvelle(s) activité(s) depuis votre dernière connexion.
                    </p>
                    
                    <!-- Liste des notifications -->
                    <div class="max-h-60 overflow-y-auto">
                        @foreach($newActivities as $activity)
                            <div class="border-b border-gray-200 last:border-b-0 py-3">
                                <div class="flex items-start">
                                    <!-- Icône -->
                                    <div class="mr-3 flex-shrink-0">
                                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100">
                                            {!! str_replace('currentColor', '#4F46E5', app('App\Http\Livewire\NotificationIndicator')->getNotificationIcon($activity)) !!}
                                        </div>
                                    </div>
                                    
                                    <!-- Contenu -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $activity['title'] }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $activity['content'] }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ \Carbon\Carbon::parse($activity['created_at'])->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Pied de page -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        wire:click="markAllAsRead"
                        type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Marquer tout comme lu
                    </button>
                    <button 
                        wire:click="dismissNotifications"
                        type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div> 