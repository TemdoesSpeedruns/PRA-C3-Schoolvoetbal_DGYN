@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="text-3xl font-bold text-gray-900">📊 Admin Controle Panel</h1>
            <p class="text-gray-600 mt-2">Beheer toernooien, scholen, wedstrijden en gebruikers</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-3xl">🏫</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Scholen Totaal</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_schools'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-3xl">✅</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Goedgekeurd</dt>
                            <dd class="text-lg font-medium text-green-600">{{ $stats['approved_schools'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-3xl">⚽</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Toernooien</dt>
                            <dd class="text-lg font-medium text-blue-600">{{ $stats['total_tournaments'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-3xl">👥</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Admins</dt>
                            <dd class="text-lg font-medium text-purple-600">{{ $stats['admin_users'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex flex-wrap -mb-px" aria-label="Tabs">
                    <button onclick="showTab('overview')" class="tab-btn active py-4 px-6 border-b-2 border-blue-500 font-medium text-sm text-blue-600 hover:text-blue-700">
                        📋 Overzicht
                    </button>
                    <button onclick="showTab('schools')" class="tab-btn py-4 px-6 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        🏫 Scholen
                    </button>
                    <button onclick="showTab('tournaments')" class="tab-btn py-4 px-6 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        🎯 Toernooien
                    </button>
                    <button onclick="showTab('pools')" class="tab-btn py-4 px-6 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        🔀 Poules
                    </button>
                    <button onclick="showTab('matches')" class="tab-btn py-4 px-6 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        ⚽ Wedstrijden
                    </button>
                    <button onclick="showTab('users')" class="tab-btn py-4 px-6 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        👥 Gebruikers
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->

        <!-- OVERVIEW TAB -->
        <div id="overview" class="tab-content block">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Pending Schools -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">⏳ Lopende Aanmeldingen</h3>
                    </div>
                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                        @forelse($schools->where('status', 'pending') as $school)
                            <div class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $school->name }}</p>
                                <p class="text-xs text-gray-500">{{ $school->email }}</p>
                                <div class="mt-2 flex gap-2">
                                    <form action="{{ route('admin.schools.approve', $school->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                                            Goedkeuren
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.schools.edit', $school->id) }}" class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                        Bekijken
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-4 text-sm text-gray-500">
                                Geen lopende aanmeldingen
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Matches -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">🎮 Recente Wedstrijden</h3>
                    </div>
                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                        @forelse($matches as $match)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $match->homeSchool?->name ?? 'Team A' }} vs 
                                            {{ $match->awaySchool?->name ?? 'Team B' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $match->tournament?->name ?? 'Onbekend' }}
                                        </p>
                                    </div>
                                    @if($match->scheduled_time)
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                            Gepland
                                        </span>
                                    @else
                                        <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                            Ongepland
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-4 text-sm text-gray-500">
                                Geen wedstrijden
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- SCHOOLS TAB -->
        <div id="schools" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">🏫 Scholen Beheer</h3>
                    <a href="{{ route('admin.schools.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                        Volledig Overzicht →
                    </a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($schools as $school)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $school->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $school->email }}</p>
                                    <p class="text-xs text-gray-500">Contactpersoon: {{ $school->contact_person }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 rounded text-xs font-medium
                                        @if($school->status === 'approved') bg-green-100 text-green-800
                                        @elseif($school->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif
                                    ">
                                        {{ ucfirst($school->status) }}
                                    </span>
                                    @if($school->pool)
                                        <span class="px-3 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            Poule {{ $school->pool->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-sm text-gray-500">
                            Geen scholen gevonden
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- TOURNAMENTS TAB -->
        <div id="tournaments" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">🎯 Toernooien Beheer</h3>
                    <a href="{{ route('admin.tournaments.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                        Volledig Overzicht →
                    </a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($tournaments as $tournament)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $tournament->name }}</p>
                                    <p class="text-xs text-gray-500">Type: {{ ucfirst($tournament->type) }} | Jaar: {{ $tournament->year }}</p>
                                    <p class="text-xs text-gray-500">Poules: {{ $tournament->pools->count() }} | Scholen: {{ $tournament->pools->sum(fn($p) => $p->schools->count()) }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 rounded text-xs font-medium
                                        @if($tournament->status === 'active') bg-green-100 text-green-800
                                        @elseif($tournament->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ ucfirst($tournament->status) }}
                                    </span>
                                    <a href="{{ route('admin.tournaments.edit', $tournament->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                        Bewerk
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-sm text-gray-500">
                            Geen toernooien gevonden
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- POOLS TAB -->
        <div id="pools" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">🔀 Poules Overzicht</h3>
                    <a href="{{ route('admin.pools.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                        Volledig Overzicht →
                    </a>
                </div>
                <div class="p-6">
                    @forelse($tournaments as $tournament)
                        @if($tournament->pools->isNotEmpty())
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ $tournament->name }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    @foreach($tournament->pools as $pool)
                                        <div class="border border-gray-200 rounded p-4">
                                            <p class="font-medium text-gray-900 mb-2">Poule {{ $pool->name }}</p>
                                            <p class="text-sm text-gray-600 mb-2">{{ $pool->schools->count() }}/4 Scholen</p>
                                            <ul class="text-xs space-y-1">
                                                @forelse($pool->schools as $school)
                                                    <li class="text-gray-700">• {{ $school->name }}</li>
                                                @empty
                                                    <li class="text-gray-400 italic">Leeg</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <p class="text-sm text-gray-500">Geen toernooien met poules</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- MATCHES TAB -->
        <div id="matches" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">⚽ Wedstrijden Beheer</h3>
                    <a href="{{ route('admin.scores.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                        Volledig Overzicht →
                    </a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($matches as $match)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $match->homeSchool?->name ?? 'Team A' }} 
                                        <span class="text-gray-500">vs</span>
                                        {{ $match->awaySchool?->name ?? 'Team B' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $match->tournament?->name ?? 'Onbekend' }}</p>
                                    @if($match->scheduled_time)
                                        <p class="text-xs text-gray-500">⏰ {{ $match->scheduled_time->format('d-m-Y H:i') }}</p>
                                    @endif
                                </div>
                                <a href="{{ route('admin.scores.edit', $match->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                    Bewerk
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-sm text-gray-500">
                            Geen wedstrijden
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- USERS TAB -->
        <div id="users" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">👥 Gebruikers Beheer</h3>
                    <a href="{{ route('manage.users') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                        Volledig Overzicht →
                    </a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($users as $user)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($user->is_admin)
                                        <span class="px-3 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                            👑 Admin
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            User
                                        </span>
                                    @endif
                                    <form action="{{ route('manage.users.toggleAdmin', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-900">
                                            @if($user->is_admin) Demote @else Promote @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-sm text-gray-500">
                            Geen gebruikers gevonden
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.add('hidden'));
    
    // Remove active class from all buttons
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active');
        btn.classList.remove('border-blue-500', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab
    document.getElementById(tabName).classList.remove('hidden');
    
    // Add active class to clicked button
    event.target.classList.add('active');
    event.target.classList.remove('border-transparent', 'text-gray-500');
    event.target.classList.add('border-blue-500', 'text-blue-600');
}
</script>
@endsection
