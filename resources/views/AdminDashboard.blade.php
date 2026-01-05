@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-blue-50 border border-blue-300 rounded-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-blue-900 mb-2">🎛️ Admin Controle Panel</h1>
        <p class="text-blue-700">Het volledige admin panel vind je hier:</p>
        <a href="{{ route('admin.panel') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-black font-bold py-3 px-6 rounded-lg text-lg">
            → Naar Admin Controle Panel
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
            <h5 class="text-sm text-gray-600 mb-1">🏫 Scholen</h5>
            <p class="text-3xl font-bold text-blue-600">{{ $totalTeams ?? 0 }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
            <h5 class="text-sm text-gray-600 mb-1">⚽ Toernooien</h5>
            <p class="text-3xl font-bold text-green-600">{{ $totalEvents ?? 0 }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
            <h5 class="text-sm text-gray-600 mb-1">👥 Gebruikers</h5>
            <p class="text-3xl font-bold text-purple-600">{{ $totalUsers ?? 0 }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
            <h5 class="text-sm text-gray-600 mb-1">🎮 Wedstrijden</h5>
            <p class="text-3xl font-bold text-orange-600">{{ $totalMatches ?? 0 }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('admin.panel') }}" class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
            <div class="text-4xl mb-3">📋</div>
            <h5 class="font-semibold text-black mb-2">Admin Panel</h5>
            <p class="text-sm text-gray-600">Alles beheren op één pagina</p>
        </a>

        <a href="{{ route('admin.schools.index') }}" class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
            <div class="text-4xl mb-3">🏫</div>
            <h5 class="font-semibold text-black mb-2">Scholen Beheer</h5>
            <p class="text-sm text-gray-600">Goedkeuren en afwijzen</p>
        </a>

        <a href="{{ route('admin.pools.index') }}" class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
            <div class="text-4xl mb-3">🔀</div>
            <h5 class="font-semibold text-black mb-2">Poules</h5>
            <p class="text-sm text-gray-600">Groepen overzicht</p>
        </a>

        <a href="{{ route('admin.tournaments.index') }}" class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
            <div class="text-4xl mb-3">🎯</div>
            <h5 class="font-semibold text-black mb-2">Toernooien</h5>
            <p class="text-sm text-gray-600">Beheer toernooien</p>
        </a>

        <a href="{{ route('admin.scores.index') }}" class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
            <div class="text-4xl mb-3">⚽</div>
            <h5 class="font-semibold text-black mb-2">Wedstrijden</h5>
            <p class="text-sm text-gray-600">Scores invoeren</p>
        </a>

        <a href="{{ route('manage.users') }}" class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition">
            <div class="text-4xl mb-3">👥</div>
            <h5 class="font-semibold text-black mb-2">Gebruikers</h5>
            <p class="text-sm text-gray-600">Admins beheren</p>
        </a>
    </div>
</div>
@endsection
