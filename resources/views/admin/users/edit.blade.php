@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Modifier l'utilisateur</h1>
        <a href="{{ route('users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
            Retour à la liste
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Adresse email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe (laisser vide pour conserver le même)</label>
                <input type="password" name="password" id="password"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Rôles</label>
                <div class="bg-gray-50 p-3 rounded border">
                    @foreach($roles as $role)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->id }}" 
                                   {{ in_array($role->id, $userRoles) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="role_{{ $role->id }}" class="ml-2 block text-sm text-gray-900">
                                {{ $role->name }} - <span class="text-xs text-gray-500">{{ $role->description ?? 'Aucune description' }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('roles')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection