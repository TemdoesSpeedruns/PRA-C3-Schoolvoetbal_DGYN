@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6">Score Invoeren</h1>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-gray-600 text-sm">Thuisploeg</p>
                <p class="text-xl font-bold">{{ $match->homeSchool->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Uitploeg</p>
                <p class="text-xl font-bold">{{ $match->awaySchool->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Toernooi</p>
                <p class="text-lg font-semibold">{{ $match->tournament->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Datum</p>
                <p class="text-lg font-semibold">{{ $match->match_date?->format('d-m-Y H:i') }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.scores.update', $match) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <label for="home_goals" class="block text-sm font-semibold mb-2">
                    {{ $match->homeSchool->name }} - Doelpunten
                </label>
                <input type="number" id="home_goals" name="home_goals" min="0" max="99"
                    value="{{ old('home_goals', $match->home_goals) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-2xl font-bold text-center"
                    required>
                @error('home_goals')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-end justify-center mb-2">
                <span class="text-3xl font-bold text-gray-400">-</span>
            </div>

            <div>
                <label for="away_goals" class="block text-sm font-semibold mb-2">
                    {{ $match->awaySchool->name }} - Doelpunten
                </label>
                <input type="number" id="away_goals" name="away_goals" min="0" max="99"
                    value="{{ old('away_goals', $match->away_goals) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 text-2xl font-bold text-center"
                    required>
                @error('away_goals')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="status" class="block text-sm font-semibold mb-2">Status</label>
            <select id="status" name="status"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="scheduled" @selected($match->status === 'scheduled')>Gepland</option>
                <option value="live" @selected($match->status === 'live')>Live</option>
                <option value="completed" @selected($match->status === 'completed')>Voltooid</option>
            </select>
            @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="notes" class="block text-sm font-semibold mb-2">Opmerkingen (scheidsrechter)</label>
            <textarea id="notes" name="notes" rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Bijvoorbeeld: rode kaarten, blessures, etc.">{{ old('notes', $match->notes) }}</textarea>
            @error('notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">
                Score Opslaan
            </button>
            <a href="{{ route('admin.scores.index') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded hover:bg-gray-400 font-semibold">
                Annuleren
            </a>
        </div>
    </form>
</div>
@endsection