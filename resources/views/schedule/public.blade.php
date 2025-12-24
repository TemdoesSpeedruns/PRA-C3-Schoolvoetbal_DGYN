@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold mb-2">📅 Wedstrijdschema</h1>
        <p class="text-gray-600">Bekijk alle geplande wedstrijden en speeltijden</p>
    </div>

    @if($matches->isEmpty())
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
            Er zijn nog geen wedstrijden ingepland.
        </div>
    @else
        <!-- Filters -->
        <div class="mb-6 flex gap-4 flex-wrap">
            <select id="tournamentFilter" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Alle toernooien</option>
                @foreach($tournaments as $tournament)
                    <option value="{{ $tournament->id }}">{{ $tournament->name }}</option>
                @endforeach
            </select>

            <select id="fieldFilter" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Alle velden</option>
                @foreach($fields as $field)
                    <option value="{{ $field->id }}">{{ $field->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Schedule Tabel -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-purple-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">Tijd</th>
                        <th class="px-6 py-3 text-left">Toernooi</th>
                        <th class="px-6 py-3 text-left">Thuisploeg</th>
                        <th class="px-6 py-3 text-center">VS</th>
                        <th class="px-6 py-3 text-left">Uitploeg</th>
                        <th class="px-6 py-3 text-left">Veld</th>
                        <th class="px-6 py-3 text-left">Scheidsrechter</th>
                        <th class="px-6 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matches as $match)
                        @if($match->scheduled_time)
                            <tr class="border-b hover:bg-gray-50 match-row" 
                                data-tournament="{{ $match->tournament_id }}"
                                data-field="{{ $match->field_id ?? '' }}">
                                <td class="px-6 py-4 text-sm font-semibold">
                                    {{ $match->scheduled_time?->format('d-m H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $match->tournament->name }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="font-semibold text-blue-600">{{ $match->homeSchool->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">⚽</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="font-semibold text-green-600">{{ $match->awaySchool->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($match->field)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                            {{ $match->field->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($match->referee)
                                        {{ $match->referee->name }}
                                    @else
                                        <span class="text-gray-500 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($match->status === 'scheduled') bg-yellow-100 text-yellow-800
                                        @elseif($match->status === 'live') bg-red-100 text-red-800
                                        @elseif($match->status === 'completed') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($match->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Legenda -->
        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                <p class="text-sm text-gray-600">Status: Gepland</p>
                <p class="font-semibold text-yellow-700">Wedstrijd staat gepland</p>
            </div>
            <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                <p class="text-sm text-gray-600">Status: Live</p>
                <p class="font-semibold text-red-700">Wedstrijd bezig</p>
            </div>
            <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                <p class="text-sm text-gray-600">Status: Voltooid</p>
                <p class="font-semibold text-green-700">Wedstrijd afgelopen</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                <p class="text-sm text-gray-600">⚽ VS</p>
                <p class="font-semibold text-blue-700">Voetbal/Lijnbal wedstrijd</p>
            </div>
        </div>
    @endif
</div>

<script>
document.getElementById('tournamentFilter')?.addEventListener('change', filterMatches);
document.getElementById('fieldFilter')?.addEventListener('change', filterMatches);

function filterMatches() {
    const tournamentId = document.getElementById('tournamentFilter')?.value || '';
    const fieldId = document.getElementById('fieldFilter')?.value || '';
    
    document.querySelectorAll('.match-row').forEach(row => {
        const showTournament = !tournamentId || row.dataset.tournament === tournamentId;
        const showField = !fieldId || row.dataset.field === fieldId;
        row.style.display = (showTournament && showField) ? '' : 'none';
    });
}
</script>
@endsection
