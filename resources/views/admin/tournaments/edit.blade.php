@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Toernooi Bewerken</h1>

    @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tournaments.update', $tournament) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PATCH')

        <div class="mb-6">
            <label for="name" class="block text-sm font-semibold mb-2">Naam</label>
            <input type="text" id="name" name="name" value="{{ old('name', $tournament->name) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required readonly>
        </div>

        <div class="mb-6">
            <label for="type" class="block text-sm font-semibold mb-2">Type</label>
            <input type="text" id="type" value="{{ ucfirst($tournament->type) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                readonly disabled>
        </div>

        <div class="mb-6">
            <label for="year" class="block text-sm font-semibold mb-2">Jaar</label>
            <input type="text" id="year" value="{{ $tournament->year }}"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                readonly disabled>
        </div>

        <div class="mb-6">
            <label for="winner" class="block text-sm font-semibold mb-2">Winnaar (School)</label>
            <input type="text" id="winner" name="winner" value="{{ old('winner', $tournament->winner) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Voer de winnende school in (optioneel)">
            @error('winner')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="status" class="block text-sm font-semibold mb-2">Status</label>
            <select id="status" name="status"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
                <option value="active" @selected($tournament->status === 'active')>Actief</option>
                <option value="completed" @selected($tournament->status === 'completed')>Voltooid</option>
            </select>
            @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">
                Bijwerken
            </button>
            <a href="{{ route('admin.tournaments.index') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded hover:bg-gray-400 font-semibold">
                Annuleren
            </a>
        </div>
    </form>
</div>
@endsection
