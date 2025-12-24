@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">School Aanvragen</h1>
        <p class="text-gray-600 mt-2">Beoordeel en keur schoolaanmeldingen goed of af</p>
    </div>

    @if(session('status'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="mb-6 flex gap-2 border-b border-gray-300">
        <a href="{{ route('admin.schools.index', ['status' => 'all']) }}"
            class="px-4 py-2 {{ $status === 'all' ? 'border-b-2 border-purple-600 text-purple-600 font-semibold' : 'text-gray-600' }}">
            Alle ({{ $schools->total() }})
        </a>
        <a href="{{ route('admin.schools.index', ['status' => 'pending']) }}"
            class="px-4 py-2 {{ $status === 'pending' ? 'border-b-2 border-purple-600 text-purple-600 font-semibold' : 'text-gray-600' }}">
            In behandeling
        </a>
        <a href="{{ route('admin.schools.index', ['status' => 'approved']) }}"
            class="px-4 py-2 {{ $status === 'approved' ? 'border-b-2 border-purple-600 text-purple-600 font-semibold' : 'text-gray-600' }}">
            Goedgekeurd
        </a>
        <a href="{{ route('admin.schools.index', ['status' => 'rejected']) }}"
            class="px-4 py-2 {{ $status === 'rejected' ? 'border-b-2 border-purple-600 text-purple-600 font-semibold' : 'text-gray-600' }}">
            Afgewezen
        </a>
    </div>

    @if($schools->count() > 0)
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">#</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">School</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Contact Persoon</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">E-mail</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Categorie</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Ingediend</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schools as $school)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $school->id }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $school->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $school->contact_person }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <a href="mailto:{{ $school->email }}" class="text-blue-600 hover:underline">
                                    {{ $school->email }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded text-xs font-semibold bg-purple-100 text-purple-800">
                                    {{ $school->category ? ucfirst($school->category) : 'Niet ingesteld' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($school->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($school->status === 'approved') bg-green-100 text-green-800
                                    @elseif($school->status === 'rejected') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($school->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $school->created_at->format('d-m-Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center flex-wrap">
                                    <a href="{{ route('admin.schools.edit', $school) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                        ✏️ Bewerk
                                    </a>
                                    @if($school->isPending())
                                        <form method="POST"
                                              action="{{ route('admin.schools.approve', $school->id) }}"
                                              class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                                ✓ Goedkeuren
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="{{ route('admin.schools.reject', $school->id) }}"
                                              class="inline"
                                              onsubmit="return confirm('Weet je zeker dat je deze school wilt afwijzen?');">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                                ✗ Afwijzen
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST"
                                              action="{{ route('admin.schools.destroy', $school->id) }}"
                                              class="inline"
                                              onsubmit="return confirm('Weet je zeker dat je deze school wilt verwijderen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                                🗑️ Verwijderen
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $schools->links() }}
        </div>
    @else
        <div class="bg-gray-100 rounded-lg p-8 text-center text-gray-600">
            <p class="text-lg">Geen schoolaanvragen gevonden met status "{{ $status }}".</p>
        </div>
    @endif
</div>
@endsection
