@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold mb-2">👨‍⚖️ Scheidsrechters Beheren</h1>
            <p class="text-gray-600">Voeg scheidsrechters toe en beheer hun beschikbaarheid</p>
        </div>
        <a href="{{ route('admin.schedule.index') }}" class="bg-purple-600 text-black px-4 py-2 rounded-lg hover:bg-purple-700">
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
        <!-- Add Referee Form -->
        <div class="col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6">➕ Nieuwe Scheidsrechter</h2>
                <form action="{{ route('admin.schedule.referees.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold mb-2">Naam</label>
                        <input type="text" id="name" name="name" placeholder="bijv. Jan Jansen"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required value="{{ old('name') }}">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold mb-2">Email</label>
                        <input type="email" id="email" name="email" placeholder="jan@example.com"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required value="{{ old('email') }}">
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-semibold mb-2">Type</label>
                        <select id="type" name="type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required onchange="toggleSchoolField()">
                            <option value="">-- Kies een type --</option>
                            <option value="school" {{ old('type') === 'school' ? 'selected' : '' }}>🏫 School</option>
                            <option value="external" {{ old('type') === 'external' ? 'selected' : '' }}>👤 Extern</option>
                        </select>
                    </div>

                    <!-- School Field (conditionally shown) -->
                    <div id="schoolField" class="hidden">
                        <label for="school_id" class="block text-sm font-semibold mb-2">School</label>
                        <select id="school_id" name="school_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Kies een school --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-black px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold">
                        ➕ Scheidsrechter Toevoegen
                    </button>
                </form>
            </div>
        </div>

        <!-- Referees List -->
        <div class="col-span-2">
            @if($referees->isEmpty())
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
                    Nog geen scheidsrechters toegevoegd. Voeg je eerste scheidsrechter toe!
                </div>
            @else
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-blue-600 text-black">
                            <tr>
                                <th class="px-6 py-3 text-left">Naam</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-center">Type</th>
                                <th class="px-6 py-3 text-center">School</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <th class="px-6 py-3 text-center">Wedstrijden</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referees as $referee)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-semibold">{{ $referee->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $referee->email }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 {{ $referee->type === 'school' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800' }} rounded-full text-sm font-semibold">
                                            {{ $referee->type === 'school' ? '🏫 School' : '👤 Extern' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm">
                                        @if($referee->school)
                                            {{ $referee->school->name }}
                                        @else
                                            <span class="text-gray-500 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($referee->is_active)
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
                                            {{ $referee->matches()->whereNotNull('scheduled_time')->count() }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Statistics -->
                <div class="mt-6 grid grid-cols-3 gap-4">
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                        <div class="text-3xl font-bold text-blue-600">{{ $referees->count() }}</div>
                        <div class="text-sm text-gray-600">Totaal scheidsrechters</div>
                    </div>
                    <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                        <div class="text-3xl font-bold text-green-600">{{ $referees->where('is_active', true)->count() }}</div>
                        <div class="text-sm text-gray-600">Actieve scheidsrechters</div>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 p-4 rounded-lg">
                        <div class="text-3xl font-bold text-purple-600">{{ $referees->where('type', 'school')->count() }}</div>
                        <div class="text-sm text-gray-600">Van school</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Types Info -->
    <div class="mt-8 grid grid-cols-2 gap-6">
        <div class="bg-purple-50 border border-purple-200 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-purple-900 mb-4">🏫 School Scheidsrechters</h3>
            <ul class="text-sm text-purple-800 space-y-2">
                <li>✓ Scheidsrechter van een deelnemende school</li>
                <li>✓ Gekoppeld aan hun school</li>
                <li>✓ Kunnen zelf hun beschikbaarheid aangeven</li>
                <li>✓ Ontvangen notificaties via email</li>
            </ul>
        </div>
        <div class="bg-orange-50 border border-orange-200 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-orange-900 mb-4">👤 Externe Scheidsrechters</h3>
            <ul class="text-sm text-orange-800 space-y-2">
                <li>✓ Onafhankelijke scheidsrechter</li>
                <li>✓ Niet gekoppeld aan een school</li>
                <li>✓ Centraal ingepland door bestuur</li>
                <li>✓ Kunnen meerdere toernooien arbitreren</li>
            </ul>
        </div>
    </div>
</div>

<script>
function toggleSchoolField() {
    const type = document.getElementById('type').value;
    const schoolField = document.getElementById('schoolField');
    if (type === 'school') {
        schoolField.classList.remove('hidden');
    } else {
        schoolField.classList.add('hidden');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('type').value === 'school') {
        document.getElementById('schoolField').classList.remove('hidden');
    }
});
</script>
@endsection
