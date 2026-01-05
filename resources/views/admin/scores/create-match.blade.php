@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Wedstrijd Toevoegen</h1>

    <form action="{{ route('admin.scores.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="mb-6">
            <label for="tournament_id" class="block text-sm font-semibold mb-2">Toernooi</label>
            <select id="tournament_id" name="tournament_id"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
                <option value="">-- Kies een toernooi --</option>
                @foreach ($tournaments as $tournament)
                    <option value="{{ $tournament->id }}" @selected(old('tournament_id') == $tournament->id)>
                        {{ $tournament->name }} ({{ $tournament->year }}) - {{ ucfirst($tournament->type) }}
                    </option>
                @endforeach
            </select>
            @error('tournament_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="home_school_id" class="block text-sm font-semibold mb-2">Thuisploeg</label>
            <select id="home_school_id" name="home_school_id"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
                <option value="">-- Kies een school --</option>
                @foreach ($schools as $school)
                    <option value="{{ $school->id }}" @selected(old('home_school_id') == $school->id)>
                        {{ $school->name }}
                    </option>
                @endforeach
            </select>
            @error('home_school_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="away_school_id" class="block text-sm font-semibold mb-2">Uitploeg</label>
            <select id="away_school_id" name="away_school_id"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
                <option value="">-- Kies een school --</option>
                @foreach ($schools as $school)
                    <option value="{{ $school->id }}" @selected(old('away_school_id') == $school->id)>
                        {{ $school->name }}
                    </option>
                @endforeach
            </select>
            @error('away_school_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="match_date" class="block text-sm font-semibold mb-2">Datum & Tijd</label>
            <input type="datetime-local" id="match_date" name="match_date"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            @error('match_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="notes" class="block text-sm font-semibold mb-2">Opmerkingen</label>
            <textarea id="notes" name="notes" rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Optioneel"></textarea>
            @error('notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-black px-6 py-2 rounded hover:bg-blue-700 font-semibold">
                Wedstrijd Toevoegen
            </button>
            <a href="{{ route('admin.scores.index') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded hover:bg-gray-400 font-semibold">
                Annuleren
            </a>
        </div>
    </form>
</div>
@endsection