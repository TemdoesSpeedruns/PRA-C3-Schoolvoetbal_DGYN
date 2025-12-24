@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold mb-2">📅 Wedstrijdschema Planning</h1>
            <p class="text-gray-600">Plan wedstrijden in, wijs velden en scheidsrechters toe</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.schedule.fields.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                ⚽ Velden Beheren
            </a>
            <a href="{{ route('admin.schedule.referees.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                👨‍⚖️ Scheidsrechters
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            ✗ {{ session('error') }}
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="mb-6 flex gap-2 border-b border-gray-300">
        <a href="{{ route('admin.schedule.index', ['status' => 'all']) }}"
            class="px-4 py-2 {{ request('status', 'all') === 'all' ? 'border-b-2 border-purple-600 text-purple-600 font-semibold' : 'text-gray-600' }}">
            Alle Wedstrijden
        </a>
        <a href="{{ route('admin.schedule.index', ['status' => 'unscheduled']) }}"
            class="px-4 py-2 {{ request('status') === 'unscheduled' ? 'border-b-2 border-purple-600 text-purple-600 font-semibold' : 'text-gray-600' }}">
            ⏳ Niet Ingepland
        </a>
        <a href="{{ route('admin.schedule.index', ['status' => 'scheduled']) }}"
            class="px-4 py-2 {{ request('status') === 'scheduled' ? 'border-b-2 border-purple-600 text-purple-600 font-semibold' : 'text-gray-600' }}">
            📅 Ingepland
        </a>
        <a href="{{ route('admin.schedule.index', ['status' => 'completed']) }}"
            class="px-4 py-2 {{ request('status') === 'completed' ? 'border-b-2 border-purple-600 text-purple-600 font-semibold' : 'text-gray-600' }}">
            ✓ Afgelopen
        </a>
    </div>

    @if($matches->isEmpty())
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
            Geen wedstrijden beschikbaar.
        </div>
    @else
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-purple-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">Toernooi</th>
                        <th class="px-6 py-3 text-left">Thuisploeg</th>
                        <th class="px-6 py-3 text-left">Uitploeg</th>
                        <th class="px-6 py-3 text-left">Geplande Tijd</th>
                        <th class="px-6 py-3 text-left">Veld</th>
                        <th class="px-6 py-3 text-left">Scheidsrechter</th>
                        <th class="px-6 py-3 text-center">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matches as $match)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-semibold">{{ $match->tournament->name }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="text-blue-600 font-semibold">{{ $match->homeSchool->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="text-green-600 font-semibold">{{ $match->awaySchool->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($match->scheduled_time)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        {{ $match->scheduled_time->format('d-m H:i') }}
                                    </span>
                                @else
                                    <span class="text-gray-500 italic">Niet ingepland</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($match->field)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                        {{ $match->field->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($match->referee)
                                    {{ $match->referee->name }}
                                @else
                                    <span class="text-gray-500 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button 
                                    data-match-id="{{ $match->id }}"
                                    data-match-title="{{ $match->homeSchool->name }} vs {{ $match->awaySchool->name }}"
                                    onclick="openScheduleModalFromButton(this)"
                                    class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                    📅 Plan in
                                </button>
                                @if($match->scheduled_time)
                                    <form action="{{ route('admin.schedule.unschedule', $match) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm ml-2"
                                            onclick="return confirm('Wil je de planning verwijderen?')">
                                            ✗ Verwijder
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Schedule Modal -->
<div id="scheduleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl w-full">
        <h2 class="text-2xl font-bold mb-4">📅 Wedstrijd Inplannen</h2>
        <p class="text-gray-600 mb-6" id="matchTitle"></p>

        <form id="scheduleForm" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <!-- Datum/Tijd -->
                <div>
                    <label for="scheduled_time" class="block text-sm font-semibold mb-2">Datum en Tijd</label>
                    <input type="datetime-local" id="scheduled_time" name="scheduled_time" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                        required>
                    @error('scheduled_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Veld -->
                <div>
                    <label for="field_id" class="block text-sm font-semibold mb-2">Veld</label>
                    <select id="field_id" name="field_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                        required>
                        <option value="">-- Kies een veld --</option>
                        @foreach($fields as $field)
                            <option value="{{ $field->id }}">{{ $field->name }} ({{ $field->type }})</option>
                        @endforeach
                    </select>
                    @error('field_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Scheidsrechter -->
            <div>
                <label for="referee_id" class="block text-sm font-semibold mb-2">Scheidsrechter (Optioneel)</label>
                <select id="referee_id" name="referee_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">-- Geen scheidsrechter --</option>
                    @foreach($referees as $referee)
                        <option value="{{ $referee->id }}">
                            {{ $referee->name }}
                            @if($referee->school)
                                ({{ $referee->school->name }})
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('referee_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mt-6">
                <h4 class="font-semibold text-blue-900 mb-2">ℹ️ Automatische Controle</h4>
                <ul class="text-sm text-blue-800 list-disc list-inside space-y-1">
                    <li>Veld beschikbaarheid wordt gecontroleerd</li>
                    <li>Scheidsrechter dubbelboeking wordt voorkomen</li>
                    <li>Team conflicten worden gedetecteerd</li>
                    <li>Pauzes tussen wedstrijden worden ingehouden</li>
                </ul>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 mt-8">
                <button type="submit" class="flex-1 bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 font-semibold">
                    ✓ Plan in
                </button>
                <button type="button" onclick="closeScheduleModal()" class="flex-1 bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 font-semibold">
                    Annuleren
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openScheduleModalFromButton(button) {
    const matchId = button.getAttribute('data-match-id');
    const matchTitle = button.getAttribute('data-match-title');
    openScheduleModal(matchId, matchTitle);
}

function openScheduleModal(matchId, matchTitle) {
    document.getElementById('matchTitle').textContent = matchTitle;
    document.getElementById('scheduleForm').action = `/admin/schedule/${matchId}/schedule`;
    document.getElementById('scheduleModal').classList.remove('hidden');
    
    // Set default time to now + 1 hour
    const now = new Date();
    now.setHours(now.getHours() + 1);
    document.getElementById('scheduled_time').value = now.toISOString().slice(0, 16);
}

function closeScheduleModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
}

// Close on escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeScheduleModal();
});
</script>
@endsection
