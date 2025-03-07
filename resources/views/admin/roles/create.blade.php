@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Créer un nouveau rôle</h1>
        <a href="{{ route('roles.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
            Retour à la liste
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form method="POST" action="{{ route('roles.store') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Le nom du rôle sera utilisé pour l'affichage.</p>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description (optionnel)</label>
                <textarea name="description" id="description" rows="3"
                         class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Une brève description des permissions et responsabilités associées à ce rôle.</p>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Créer le rôle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection