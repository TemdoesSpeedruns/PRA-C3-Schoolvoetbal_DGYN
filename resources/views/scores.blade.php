@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-8">Wedstrijdresultaten</h1>

    @if ($liveMatches->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4 text-red-600">🔴 Live Wedstrijden</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($liveMatches as $match)
                    <div class="bg-red-50 border-2 border-red-500 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-2">{{ $match->tournament->name }}</p>
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-center flex-1">
                                <p class="font-bold text-lg">{{ $match->homeSchool->name }}</p>
                                <p class="text-3xl font-bold text-red-600">{{ $match->home_goals ?? '-' }}</p>
                            </div>
                            <div class="px-4 text-2xl font-bold text-gray-400">-</div>
                            <div class="text-center flex-1">
                                <p class="font-bold text-lg">{{ $match->awaySchool->name }}</p>
                                <p class="text-3xl font-bold text-red-600">{{ $match->away_goals ?? '-' }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 text-center">{{ $match->match_date?->format('d-m-Y H:i') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($completedMatches->count() > 0)
        <div>
            <h2 class="text-2xl font-bold mb-4">✓ Afgelopen Wedstrijden</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($completedMatches as $match)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                        <p class="text-sm text-gray-600 mb-2">{{ $match->tournament->name }}</p>
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-center flex-1">
                                <p class="font-bold text-lg">{{ $match->homeSchool->name }}</p>
                                <p class="text-3xl font-bold">{{ $match->home_goals ?? '-' }}</p>
                            </div>
                            <div class="px-4 text-2xl font-bold text-gray-400">-</div>
                            <div class="text-center flex-1">
                                <p class="font-bold text-lg">{{ $match->awaySchool->name }}</p>
                                <p class="text-3xl font-bold">{{ $match->away_goals ?? '-' }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 text-center">{{ $match->match_date?->format('d-m-Y H:i') }}</p>
                        @if ($match->notes)
                            <p class="text-xs text-gray-600 mt-2 italic">{{ $match->notes }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-gray-100 rounded-lg p-8 text-center text-gray-600">
            <p>Nog geen wedstrijdresultaten beschikbaar.</p>
        </div>
    @endif
</div>
@endsection