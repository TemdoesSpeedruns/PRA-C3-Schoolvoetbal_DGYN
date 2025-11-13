@extends('layouts.app')

@section('content')
<h1>Toernooi Historie</h1>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Datum</th>
            <th>Toernooi</th>
            <th>Winnaar</th>
            <th>Runner-up</th>
        </tr>
    </thead>
    <tbody>
        @foreach($history as $h)
            <tr>
                <td>{{ $h->date->format('d-m-Y') }}</td>
                <td>{{ $h->tournament_name }}</td>
                <td>{{ $h->winner_name }}</td>
                <td>{{ $h->runner_up ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
