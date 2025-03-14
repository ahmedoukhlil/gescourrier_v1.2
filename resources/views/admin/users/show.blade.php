@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Informations de base</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm font-medium text-gray-500">Nom</p>
            <p class="mt-1">{{ $user->name }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Adresse email</p>
            <p class="mt-1">{{ $user->email }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Service</p>
            <p class="mt-1">{{ $user->service }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Date de création</p>
            <p class="mt-1">{{ $user->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Dernière modification</p>
            <p class="mt-1">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>

        <div>
            <h2 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Rôles</h2>
            <div class="flex flex-wrap gap-2 mt-2">
                @forelse($user->roles as $role)
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                        {{ $role->name }}
                    </span>
                @empty
                    <p class="text-gray-500 italic">Aucun rôle attribué</p>
                @endforelse
            </div>
        </div>

        @if(auth()->id() !== $user->id)
        <div class="mt-8 pt-4 border-t">
            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Supprimer cet utilisateur
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection