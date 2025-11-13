@extends('layouts.app')

@section('content')
<h1>Recente Uitslagen</h1>

@foreach($results as $result)
    <div class="border p-3 my-2 rounded">
        <h4>{{ $result->tournament_name }}</h4>
        <p><strong>Winnaar:</strong> {{ $result->winner_name }}</p>
        @if($result->runner_up)
            <p><strong>Runner-up:</strong> {{ $result->runner_up }}</p>
        @endif
        <p><small>{{ $result->date->format('d-m-Y') }}</small></p>
    </div>
@endforeach
@endsection
