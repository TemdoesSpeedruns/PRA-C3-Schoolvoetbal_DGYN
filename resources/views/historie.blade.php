@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Vorige Winnaars</h1>
        <p class="text-gray-600 mb-8">Bekijk alle kampioenen van vorige jaren</p>

        @if($tournaments->isEmpty())
            <div class="bg-gray-100 rounded-lg p-8 text-center text-gray-600">
                <p class="text-lg">Er zijn nog geen afgeloten toernooien beschikbaar.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tournaments as $tournament)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white">{{ $tournament->name }}</h2>
                            <p class="text-purple-100">Jaar: {{ $tournament->year }}</p>
                        </div>

                        <!-- Content -->
                        <div class="px-6 py-6">
                            @if($tournament->winner)
                                <div class="text-center">
                                    <div class="text-5xl mb-3">🏆</div>
                                    <p class="text-gray-600 text-sm mb-2">Kampioen</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $tournament->winner }}</p>
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <p class="text-gray-500">Kampioen nog niet bekend</p>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                    {{ ucfirst($tournament->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
