<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Bienvenue dans le système de gestion de courrier</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Card Courriers Entrants -->
                        <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <h4 class="text-lg font-semibold">Courriers Entrants</h4>
                            </div>
                            <p class="text-gray-600 mb-4">Gérez les courriers entrants, assignez des destinataires et suivez leur statut.</p>
                            <a href="{{ route('courriers.index') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                                Accéder
                            </a>
                        </div>

                        <!-- Card Courriers Sortants -->
                        <div class="bg-green-50 p-6 rounded-lg shadow-md">
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                <h4 class="text-lg font-semibold">Courriers Sortants</h4>
                            </div>
                            <p class="text-gray-600 mb-4">Créez et gérez les courriers sortants, téléchargez les décharges signées.</p>
                            <a href="{{ route('courriers-sortants.index') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">
                                Accéder
                            </a>
                        </div>

                        <!-- Card Destinataires -->
                        <div class="bg-purple-50 p-6 rounded-lg shadow-md">
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <h4 class="text-lg font-semibold">Destinataires</h4>
                            </div>
                            <p class="text-gray-600 mb-4">Gérez la liste des destinataires internes pour les courriers.</p>
                            <a href="{{ route('destinataires.index') }}" class="inline-block bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-4 rounded">
                                Accéder
                            </a>
                        </div>

                        @can('manage-users')
                        <!-- Card Administration -->
                        <div class="bg-red-50 p-6 rounded-lg shadow-md">
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <h4 class="text-lg font-semibold">Administration</h4>
                            </div>
                            <p class="text-gray-600 mb-4">Gérez les utilisateurs et les rôles du système.</p>
                            <a href="{{ route('users.index') }}" class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">
                                Utilisateurs
                            </a>
                            <a href="{{ route('roles.index') }}" class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded mt-2">
                                Rôles
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>