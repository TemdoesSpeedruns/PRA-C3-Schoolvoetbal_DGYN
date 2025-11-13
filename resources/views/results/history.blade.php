@extends('layouts.app')

@section('content')
<h2>Historie Uitslagen</h2>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Datum</th>
            <th>Toernooi</th>
            <th>Winnaar</th>
            <th>Runner-up</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $h)
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
