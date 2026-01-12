@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Toernooien Beheren</h1>
            <p class="text-gray-600 mt-2">Beheer toernooien, set winnaars en voltooide status</p>
        </div>
        <a href="{{ route('admin.tournaments.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition">
            + Nieuw Toernooi
        </a>
    </div>

    @if(session('status'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="mb-6 flex gap-2 border-b border-gray-300">
        <a href="{{ route('admin.tournaments.index', ['status' => 'all']) }}"
            class="px-4 py-2 {{ $status === 'all' ? 'border-b-2 border-purple-600 text-purple-600 font-semibold' : 'text-gray-600' }}">
            Alle
        </a>
        <a href="{{ route('admin.tournaments.index', ['status' => 'active']) }}"
            class="px-4 py-2 {{ $status === 'active' ? 'border-b-2 border-purple-600 text-purple-600 font-semibold' : 'text-gray-600' }}">
            Actief
        </a>
        <a href="{{ route('admin.tournaments.index', ['status' => 'completed']) }}"
            class="px-4 py-2 {{ $status === 'completed' ? 'border-b-2 border-purple-600 text-purple-600 font-semibold' : 'text-gray-600' }}">
            Voltooid
        </a>
    </div>

    @if($tournaments->count() > 0)
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Naam</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jaar</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Winnaar</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tournaments as $tournament)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $tournament->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($tournament->type) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $tournament->year }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if($tournament->winner)
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                        🏆 {{ $tournament->winner }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($tournament->status === 'active') bg-blue-100 text-blue-800
                                    @elseif($tournament->status === 'completed') bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($tournament->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.tournaments.edit', $tournament) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                    Bewerk
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-gray-100 rounded-lg p-8 text-center text-gray-600">
            <p class="text-lg">Geen toernooien gevonden.</p>
        </div>
    @endif
</div>
@endsection
