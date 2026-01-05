@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Scores Beheren</h1>
        <a href="{{ route('admin.scores.create') }}" class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">
            Wedstrijd Toevoegen
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Toernooi</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Thuis</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Uit</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Score</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Datum</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Acties</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($matches as $match)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">{{ $match->tournament->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $match->homeSchool->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $match->awaySchool->name }}</td>
                        <td class="px-6 py-4 text-center font-bold">
                            @if ($match->home_goals !== null && $match->away_goals !== null)
                                {{ $match->home_goals }} - {{ $match->away_goals }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if ($match->status === 'completed') bg-green-100 text-green-800
                                @elseif ($match->status === 'live') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($match->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $match->match_date?->format('d-m-Y H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex gap-2 justify-center">
                                <a href="{{ route('admin.scores.edit', $match) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Bewerk
                                </a>
                                @if ($match->status === 'completed')
                                    <form action="{{ route('admin.scores.destroy', $match) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Weet je zeker dat je deze wedstrijd wilt verwijderen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                                            Verwijder
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Geen wedstrijden gevonden.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection