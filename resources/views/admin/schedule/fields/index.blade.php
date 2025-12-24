@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold mb-2">⚽ Velden Beheren</h1>
            <p class="text-gray-600">Voeg speelvelden toe en beheer hun instellingen</p>
        </div>
        <a href="{{ route('admin.schedule.index') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
            ← Terug naar Planning
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <strong>Fouten:</strong>
            <ul class="list-disc list-inside mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-3 gap-6">
        <!-- Add Field Form -->
        <div class="col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6">➕ Nieuw Veld</h2>
                <form action="{{ route('admin.schedule.fields.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold mb-2">Veldnaam</label>
                        <input type="text" id="name" name="name" placeholder="bijv. Voetbalveld 1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            required value="{{ old('name') }}">
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-semibold mb-2">Type</label>
                        <select id="type" name="type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            required>
                            <option value="">-- Kies een type --</option>
                            <option value="voetbal" {{ old('type') === 'voetbal' ? 'selected' : '' }}>⚽ Voetbal</option>
                            <option value="lijnbal" {{ old('type') === 'lijnbal' ? 'selected' : '' }}>🎯 Lijnbal</option>
                        </select>
                    </div>

                    <div>
                        <label for="capacity" class="block text-sm font-semibold mb-2">Capaciteit (personen)</label>
                        <input type="number" id="capacity" name="capacity" placeholder="bijv. 100"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                            min="1" value="{{ old('capacity', 100) }}">
                    </div>

                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-semibold">
                        ➕ Veld Toevoegen
                    </button>
                </form>
            </div>
        </div>

        <!-- Fields List -->
        <div class="col-span-2">
            @if($fields->isEmpty())
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
                    Nog geen velden toegevoegd. Voeg je eerste veld toe!
                </div>
            @else
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-green-600 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left">Naam</th>
                                <th class="px-6 py-3 text-center">Type</th>
                                <th class="px-6 py-3 text-center">Capaciteit</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <th class="px-6 py-3 text-center">Wedstrijden</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fields as $field)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-semibold">{{ $field->name }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                            {{ $field->type === 'voetbal' ? '⚽ Voetbal' : '🎯 Lijnbal' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm">{{ $field->capacity }} personen</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($field->is_active)
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                                ✓ Actief
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold">
                                                ⊘ Inactief
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm">
                                            {{ $field->matches()->whereNotNull('scheduled_time')->count() }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Statistics -->
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                        <div class="text-3xl font-bold text-blue-600">{{ $fields->count() }}</div>
                        <div class="text-sm text-gray-600">Totaal velden</div>
                    </div>
                    <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                        <div class="text-3xl font-bold text-green-600">{{ $fields->where('is_active', true)->count() }}</div>
                        <div class="text-sm text-gray-600">Actieve velden</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Types Info -->
    <div class="mt-8 grid grid-cols-2 gap-6">
        <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">⚽ Voetbal Velden</h3>
            <ul class="text-sm text-blue-800 space-y-2">
                <li>✓ Voor 3/4, 5/6, 7/8, VO-wedstrijden</li>
                <li>✓ Wedstrijdduur: 15 minuten</li>
                <li>✓ Pauze erna: 5 minuten</li>
                <li>✓ Aanbevolen: 4-6 velden</li>
            </ul>
        </div>
        <div class="bg-purple-50 border border-purple-200 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-purple-900 mb-4">🎯 Lijnbal Velden</h3>
            <ul class="text-sm text-purple-800 space-y-2">
                <li>✓ Voor basis en VO lijnbalgroepen</li>
                <li>✓ Basis duur: 10 minuten (2 min pauze)</li>
                <li>✓ VO duur: 12 minuten (geen pauze)</li>
                <li>✓ Aanbevolen: 1-2 velden</li>
            </ul>
        </div>
    </div>
</div>
@endsection
