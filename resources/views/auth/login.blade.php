<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GesCourrier') }} - Connexion</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <style>
        .login-bg {
            background-image: url('https://photo.comptoir.fr/asset/guide/ou-partir-en-mauritanie/1425/678370-1260x630-oasis-de-tergit-region-de-l-adrar-mauritanie.jpg');
            background-size: cover;
            background-position: center;
        }
        
        .glass-panel {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left side - Image -->
        <div class="hidden md:block md:w-1/2 login-bg">
            <div class="h-full w-full flex flex-col items-center justify-center bg-black bg-opacity-50 p-8">
                <h1 class="text-4xl font-bold text-white mb-4">Courriers Administratifs</h1>
                <p class="text-xl text-white text-center">GesCourrier by SYSLOG</p>
            </div>
        </div>
        
        <!-- Right side - Login form -->
        <div class="w-full md:w-1/2 flex items-center justify-center px-6 py-12 md:px-12">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <div class="w-20 h-20 flex items-center justify-center rounded-full bg-indigo-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                
                <div class="glass-panel rounded-lg p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-800">Connexion</h2>
                        <p class="text-gray-600 mt-2">Accédez à votre espace de gestion</p>
                    </div>
                    
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">
                                {{ __('Whoops! Something went wrong.') }}
                            </div>

                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <input id="email" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="email" name="email" value="{{ old('email') }}" required autofocus />
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input id="password" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="password" name="password" required autocomplete="current-password" />
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <input id="remember_me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" name="remember">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                    Se souvenir de moi
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}">
                                    Mot de passe oublié?
                                </a>
                            @endif
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Connexion
                            </button>
                        </div>
                    </form>
                    
                    @if (Route::has('register'))
                        <div class="text-center mt-4">
                            <p class="text-sm text-gray-600">
                                Pas encore de compte? 
                                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                    Créer un compte
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
                
                <div class="text-center mt-8 text-sm text-gray-600">
                    &copy; {{ date('Y') }} Système de Gestion de Courrier. Tous droits réservés.
                </div>
            </div>
        </div>
    </div>
</body>
</html>