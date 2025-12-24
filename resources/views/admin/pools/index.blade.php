@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="mb-8">
        <h1 class="text-4xl font-bold mb-4">Poules / Groepen</h1>
        
        @if($tournaments->isEmpty())
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                Geen actieve toernooien gevonden.
            </div>
        @else
            @foreach($tournaments as $tournament)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-4">
                        {{ ucfirst($tournament->type) }} - {{ $tournament->name }}
                        <span class="text-sm font-normal text-gray-600">
                            (Status: {{ $tournament->status === 'active' ? '🟢 Actief' : '🔴 Afgerond' }})
                        </span>
                    </h2>

                    @if($tournament->pools->isEmpty())
                        <div class="bg-gray-100 border border-gray-400 text-gray-700 px-4 py-3 rounded">
                            Geen poules ingesteld voor dit toernooi.
                        </div>
                    @else
                        @php
                            // Groepeer poules per categorie
                            $poolsByCategory = $tournament->pools->groupBy('category');
                        @endphp
                        
                        @foreach($poolsByCategory as $category => $categoryPools)
                            <div class="mb-6 pt-4 border-t-2 border-gray-300">
                                <h3 class="text-lg font-bold mb-3 text-blue-900">
                                    📚 Categorie: <span class="text-blue-600">{{ ucfirst($category) }}</span>
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($categoryPools as $pool)
                                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                                            <h4 class="text-xl font-bold mb-3 text-blue-700">Poule {{ $pool->name }}</h4>
                                            
                                            @if($pool->schools->isEmpty())
                                                <p class="text-gray-500 italic">Geen scholen in deze poule</p>
                                            @else
                                                <ul class="space-y-2">
                                                    @foreach($pool->schools as $school)
                                                        <li class="bg-white p-2 rounded border border-gray-200 flex justify-between items-center">
                                                            <span>{{ $school->name }}</span>
                                                            <span class="text-xs bg-green-200 text-green-800 px-2 py-1 rounded">
                                                                ✓ Goedgekeurd
                                                            </span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                
                                                <div class="mt-3 text-sm text-gray-600">
                                                    <strong>Totaal:</strong> {{ $pool->schools->count() }} 
                                                    {{ $pool->schools->count() === 1 ? 'school' : 'scholen' }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif
                </div>
            @endforeach
        @endif
    </div>

    <div class="mt-8">
        <a href="{{ route('admin.dashboard') }}" class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            ← Terug naar dashboard
        </a>
    </div>
</div>
@endsection
