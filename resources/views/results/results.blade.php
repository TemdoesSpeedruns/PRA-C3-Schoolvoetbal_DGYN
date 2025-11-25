@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Huidige Uitslagen</h1>

    @if($tournaments->isEmpty())
        <p>Er zijn momenteel geen actieve toernooien.</p>
    @else
        @foreach ($tournaments as $tournament)
            <div class="bg-white shadow rounded p-4 mb-4">
                <h2 class="text-xl font-semibold">{{ $tournament->name }} ({{ $tournament->year }})</h2>
                
                @if(!empty($tournament->results))
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($tournament->results as $match)
                            <li>{{ $match['team1'] }} {{ $match['score1'] }} - {{ $match['score2'] }} {{ $match['team2'] }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>Uitslagen zijn nog niet ingevoerd.</p>
                @endif
            </div>
        @endforeach
    @endif
</div>
@endsection
