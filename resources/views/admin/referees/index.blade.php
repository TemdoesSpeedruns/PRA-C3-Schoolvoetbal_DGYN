@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Scheidsrechters Beheren</h1>
        <a href="{{ route('referees.register.form') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Scheidsrechter Toevoegen
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Naam</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">E-mail</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Type</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Telefoon</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Ervaring (jaren)</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Acties</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($referees as $referee)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-semibold">{{ $referee->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $referee->email }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if ($referee->type === 'professional') bg-purple-100 text-purple-800
                                @elseif ($referee->type === 'senior') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($referee->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $referee->phone ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-center">
                            {{ $referee->experience ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if ($referee->is_active) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $referee->is_active ? 'Actief' : 'Inactief' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex gap-2 justify-center">
                                <a href="{{ route('admin.referees.edit', $referee) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Bewerk
                                </a>
                                <form action="{{ route('admin.referees.destroy', $referee) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Weet u zeker dat u deze scheidsrechter wilt verwijderen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                                        Verwijder
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Geen scheidsrechters aanwezig. <a href="{{ route('referees.register.form') }}" class="text-blue-600 hover:text-blue-800">Voeg er een toe</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
