@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="mb-8">
        <h1 class="text-4xl font-bold mb-4">Mijn Poule / Groep</h1>
        
        @if($school === null)
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                Je bent nog niet ingedeeld in een poule. Controleer je registratiestatus of neem contact op met de organisator.
            </div>
        @elseif($school->pool === null)
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                Je school is goedgekeurd maar nog niet ingedeeld in een poule. Dit gebeurt binnenkort.
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold mb-2">{{ $school->name }}</h2>
                    <p class="text-gray-600">Status: 
                        <span class="inline-block px-3 py-1 rounded-full 
                            @if($school->status === 'approved') bg-green-200 text-green-800
                            @elseif($school->status === 'pending') bg-yellow-200 text-yellow-800
                            @else bg-red-200 text-red-800
                            @endif">
                            {{ $school->status === 'approved' ? '✓ Goedgekeurd' : ($school->status === 'pending' ? '⏳ In afwachting' : '✗ Afgewezen') }}
                        </span>
                    </p>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <h3 class="text-xl font-bold mb-3 text-blue-700">Je Poule</h3>
                    
                    <div class="mb-4">
                        <h4 class="font-bold text-lg text-blue-900">Poule {{ $school->pool->name }}</h4>
                        <p class="text-gray-600 text-sm">{{ $school->pool->tournament->name }} ({{ ucfirst($school->pool->tournament->type) }})</p>
                    </div>

                    <h4 class="font-bold mb-2">Deelnemende scholen:</h4>
                    <ul class="space-y-2">
                        @foreach($school->pool->schools as $poolSchool)
                            <li class="bg-white p-3 rounded border border-gray-200 flex items-center">
                                @if($poolSchool->id === $school->id)
                                    <span class="text-lg mr-2">👤</span>
                                @endif
                                <span>{{ $poolSchool->name }}</span>
                                @if($poolSchool->id === $school->id)
                                    <span class="ml-auto text-xs bg-blue-500 text-white px-2 py-1 rounded">
                                        Jouw school
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-4 text-sm text-gray-600">
                        <strong>Totaal:</strong> {{ $school->pool->schools->count() }} 
                        {{ $school->pool->schools->count() === 1 ? 'school' : 'scholen' }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="mt-8 space-y-4">
        <a href="{{ route('public.scores') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            → Bekijk wedstrijduitslagen
        </a>
        <br>
        <a href="{{ route('home') }}" class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            ← Terug naar home
        </a>
    </div>
</div>
@endsection
