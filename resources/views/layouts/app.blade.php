<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Courrier - @yield('title', 'Accueil')</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-blue-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-white font-bold text-xl">Gestion de Courrier</span>
                        </div>
                        <div class="ml-6 flex items-center space-x-4">
                            <a href="{{ route('courriers.index') }}" class="text-white hover:bg-blue-500 px-3 py-2 rounded-md text-sm font-medium">Courriers Entrants</a>
                            <a href="{{ route('courriers-sortants.index') }}" class="text-white hover:bg-blue-500 px-3 py-2 rounded-md text-sm font-medium">Courriers Sortans</a>

                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-4 flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white shadow-inner py-3">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-500 text-sm">&copy; {{ date('Y') }} Gestion de Courrier. Tous droits réservés.</p>
            </div>
        </footer>
    </div>
<script>
    document.addEventListener('click', function(event) {
        // Fermer les dropdowns d'autocomplétion quand on clique ailleurs
        const dropdowns = document.querySelectorAll('[wire\\:model\\.debounce\\.300ms="destinataireSearch"]');
        let clickedInside = false;
        
        dropdowns.forEach(dropdown => {
            if (dropdown.contains(event.target) || dropdown.parentElement.contains(event.target)) {
                clickedInside = true;
            }
        });
        
        if (!clickedInside) {
            // Déclencher l'événement Livewire pour fermer les dropdowns
            window.livewire.emit('closeDropdowns');
        }
    });
</script>


   
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    @stack('scripts')
    @livewireScripts
    
</body>
</html>