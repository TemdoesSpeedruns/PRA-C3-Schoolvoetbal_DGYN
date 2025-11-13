@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nieuwe uitslag toevoegen</h1>

    <form action="{{ route('results.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Toernooi</label>
            <input type="text" name="tournament_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Winnaar</label>
            <input type="text" name="winner_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Runner-up</label>
            <input type="text" name="runner_up" class="form-control">
        </div>
        <div class="mb-3">
            <label>Datum</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Opslaan</button>
    </form>
</div>
@endsection
