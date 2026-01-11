@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white">📊 Poule Indeling</h1>
                <p class="text-slate-400 mt-2">{{ $tournament->name }}</p>
            </div>
            <a href="{{ route('admin.tournaments.index') }}" class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">
                ← Terug naar toernooien
            </a>
        </div>

        <!-- Flash Messages -->
        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                <strong>Fout!</strong>
                <ul class="mt-2">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                ✗ {{ session('error') }}
            </div>
        @endif

        @if (session('info'))
            <div class="bg-blue-500 text-white p-4 rounded-lg mb-6">
                ℹ {{ session('info') }}
            </div>
        @endif

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-slate-700 rounded-lg p-6 border border-slate-600">
                <div class="text-sm text-slate-400 mb-2">Poules aangemaakt</div>
                <div class="text-3xl font-bold text-white">{{ $pools->count() }}</div>
            </div>
            <div class="bg-slate-700 rounded-lg p-6 border border-slate-600">
                <div class="text-sm text-slate-400 mb-2">Totale scholen in poules</div>
                <div class="text-3xl font-bold text-white">{{ $pools->sum(fn($p) => $p->schools()->count()) }}</div>
            </div>
            <div class="bg-slate-700 rounded-lg p-6 border border-slate-600">
                <div class="text-sm text-slate-400 mb-2">Wachtende scholen</div>
                <div class="text-3xl font-bold text-yellow-400">{{ $pendingSchools }}</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-slate-700 rounded-lg p-6 mb-8 border border-slate-600">
            <h2 class="text-xl font-bold text-white mb-6">🎯 Acties</h2>
            <div class="grid grid-cols-1 gap-4">
                <!-- Allocate Schools -->
                <form method="POST" action="{{ route('admin.tournament.pools.allocate', $tournament) }}" onsubmit="return confirm('Weet je zeker? Dit zal {{ $pendingSchools }} scholen indelen in poules.');">
                    @csrf
                    <button type="submit" class="w-full px-6 py-6 bg-blue-600 hover:bg-blue-500 text-black rounded-lg font-extrabold text-lg lg:text-xl transition shadow-lg">
                        📋 Scholen indelen in poules
                    </button>
                </form>

                <!-- Create Matches -->
                <form method="POST" action="{{ route('admin.tournament.pools.createMatches', $tournament) }}" onsubmit="return confirm('Weet je zeker? Dit zal wedstrijden aanmaken voor alle poules.');">
                    @csrf
                    <button type="submit" class="w-full px-6 py-6 bg-green-600 hover:bg-green-500 text-black rounded-lg font-extrabold text-lg lg:text-xl transition shadow-lg">
                        ⚽ Wedstrijden aanmaken
                    </button>
                </form>

                <!-- Reset All -->
                <form method="POST" action="{{ route('admin.tournament.pools.reset', $tournament) }}" onsubmit="return confirm('⚠️ WAARSCHUWING! Dit zal ALLE poules en wedstrijden verwijderen. Dit kan niet ongedaan worden!');">
                    @csrf
                    @method('POST')
                    <button type="submit" class="w-full px-6 py-6 bg-red-600 hover:bg-red-500 text-black rounded-lg font-extrabold text-lg lg:text-xl transition shadow-lg">
                        🗑️ Alles resetten
                    </button>
                </form>
            </div>
        </div>

        <!-- Pools List -->
        <div class="space-y-6">
            @forelse ($pools as $pool)
                <div class="bg-slate-700 rounded-lg p-6 border border-slate-600">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-white">{{ $pool->name }}</h3>
                            <p class="text-slate-400 mt-1">{{ $pool->schools()->count() }} / 4 scholen</p>
                        </div>
                        <form method="POST" action="{{ route('admin.tournament.pools.destroy', [$tournament, $pool->id]) }}" onsubmit="return confirm('Deze poule verwijderen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded transition">
                                🗑️ Verwijderen
                            </button>
                        </form>
                    </div>

                    <!-- Schools in Pool -->
                    <div class="bg-slate-800 rounded p-4">
                        @if ($pool->schools()->count() > 0)
                            <ul class="space-y-2">
                                @foreach ($pool->schools()->get() as $school)
                                    <li class="flex items-center text-slate-300">
                                        <span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                        {{ $school->name }}
                                        <span class="ml-2 text-xs bg-slate-600 px-2 py-1 rounded text-slate-300">
                                            Cat. {{ $school->category }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-slate-500 italic">Geen scholen in deze poule</p>
                        @endif
                    </div>

                    <!-- Pool Stats -->
                    <div class="mt-4 grid grid-cols-3 gap-2 text-sm text-slate-400">
                        <div>
                            Wedstrijden in poule:
                            <span class="text-white font-semibold">
                                @php
                                    $schoolIds = $pool->schools()->pluck('id')->toArray();
                                    $matchCount = \App\Models\GameMatch::where('tournament_id', $tournament->id)
                                        ->where(function ($q) use ($schoolIds) {
                                            $q->whereIn('home_school_id', $schoolIds)
                                              ->orWhereIn('away_school_id', $schoolIds);
                                        })
                                        ->count();
                                @endphp
                                {{ $matchCount }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-slate-700 rounded-lg p-8 border border-slate-600 text-center">
                    <p class="text-slate-400 text-lg">Nog geen poules aangemaakt</p>
                    <p class="text-slate-500 text-sm mt-2">Druk op "Scholen indelen in poules" om te beginnen</p>
                </div>
            @endforelse
        </div>

        <!-- Info Section -->
        <div class="mt-12 bg-slate-700 rounded-lg p-6 border border-slate-600">
            <h2 class="text-xl font-bold text-white mb-4">ℹ️ Hoe werkt het?</h2>
            <ul class="space-y-3 text-slate-300">
                <li><strong>Stap 1:</strong> Druk op "Scholen indelen in poules" - Dit zal alle goedgekeurde scholen automatisch indelen in poules (max 4 per poule)</li>
                <li><strong>Stap 2:</strong> Druk op "Wedstrijden aanmaken" - Dit zal automatisch alle wedstrijden aanmaken per poule (round-robin systeem)</li>
                <li><strong>Stap 3:</strong> Ga naar Wedstrijdschema om de wedstrijden in te plannen</li>
                <li><strong>Reset:</strong> Wil je opnieuw beginnen? Druk dan op "Alles resetten"</li>
            </ul>
        </div>
    </div>
</div>
@endsection
