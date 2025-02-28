<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Courriers</title>
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex flex-col min-h-screen">
        <!-- En-tête -->
        <header class="bg-white shadow">
            <div class="container mx-auto px-4 py-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-800">Système de Gestion des Courriers</h1>
                    <!-- Menu de navigation, profil utilisateur, etc. -->
                </div>
            </div>
        </header>
        
        <!-- Contenu principal -->
        <main class="flex-grow container mx-auto px-4 py-6">
            <!-- Contenu de la page -->
            @yield('content')
            
            <!-- Important : Chargement des deux composants Livewire -->
            <div>
                @livewire('courriers-list')
                @livewire('create-courrier-modal')
            </div>
        </main>
        
        <!-- Pied de page -->
        <footer class="bg-white shadow-inner py-4">
            <div class="container mx-auto px-4">
                <p class="text-center text-gray-600 text-sm">&copy; {{ date('Y') }} - Système de Gestion des Courriers</p>
            </div>
        </footer>
    </div>
    
    @livewireScripts
    
    <!-- SweetAlert pour les confirmations -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Script pour gérer les confirmations de suppression -->
    <script>
        window.addEventListener('swal:confirm', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer!',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('delete', event.detail.id);
                }
            });
        });
    </script>
</body>
</html>