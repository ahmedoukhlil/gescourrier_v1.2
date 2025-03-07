@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Détails du rôle</h1>
        <div class="flex space-x-2">
            <a href="{{ route('roles.edit', $role) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Modifier
            </a>
            <a href="{{ route('roles.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                Retour à la liste
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Informations de base</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Nom</p>
                    <p class="mt-1">{{ $role->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Slug</p>
                    <p class="mt-1">{{ $role->slug }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-500">Description</p>
                    <p class="mt-1">{{ $role->description ?? 'Aucune description' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Date de création</p>
                    <p class="mt-1">{{ $role->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Dernière modification</p>
                    <p class="mt-1">{{ $role->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Utilisateurs avec ce rôle</h2>
            @if($role->users->count() > 0)
                <div class="mt-2 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($role->users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('users.show', $user) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 italic">Aucun utilisateur n'a ce rôle</p>
            @endif
        </div>

        @if(!in_array($role->slug, ['admin', 'gestionnaire', 'agent', 'lecteur']) && $role->users->count() == 0)
        <div class="mt-8 pt-4 border-t">
            <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Supprimer ce rôle
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection