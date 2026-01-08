@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Scheidsrechter Aanvragen</h1>
        <p class="text-gray-600 mt-2">Beoordeel en keur scheidsrechteraanmeldingen goed of af</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="mb-6 flex gap-2 border-b border-gray-300">
        <a href="{{ route('admin.referees.approvals', ['status' => 'all']) }}"
            class="px-4 py-2 {{ $status === 'all' ? 'border-b-2 border-green-600 text-green-600 font-semibold' : 'text-gray-600' }}">
            Alle ({{ $referees->total() }})
        </a>
        <a href="{{ route('admin.referees.approvals', ['status' => 'pending']) }}"
            class="px-4 py-2 {{ $status === 'pending' ? 'border-b-2 border-green-600 text-green-600 font-semibold' : 'text-gray-600' }}">
            In behandeling
        </a>
        <a href="{{ route('admin.referees.approvals', ['status' => 'approved']) }}"
            class="px-4 py-2 {{ $status === 'approved' ? 'border-b-2 border-green-600 text-green-600 font-semibold' : 'text-gray-600' }}">
            Goedgekeurd
        </a>
        <a href="{{ route('admin.referees.approvals', ['status' => 'rejected']) }}"
            class="px-4 py-2 {{ $status === 'rejected' ? 'border-b-2 border-green-600 text-green-600 font-semibold' : 'text-gray-600' }}">
            Afgewezen
        </a>
    </div>

    @if($referees->count() > 0)
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Naam</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">E-mail</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Telefoon</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Ervaring</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Ingediend</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($referees as $referee)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $referee->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <a href="mailto:{{ $referee->email }}" class="text-blue-600 hover:underline">
                                    {{ $referee->email }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if ($referee->type === 'professional') bg-purple-100 text-purple-800
                                    @elseif ($referee->type === 'senior') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($referee->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $referee->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-center">{{ $referee->experience ?? '-' }} jaar</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($referee->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($referee->status === 'approved') bg-green-100 text-green-800
                                    @elseif($referee->status === 'rejected') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($referee->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $referee->created_at->format('d-m-Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center flex-wrap">
                                    @if($referee->status === 'pending')
                                        <form action="{{ route('admin.referees.approve', $referee) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-semibold">
                                                ✓ Goedkeuren
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.referees.reject', $referee) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-semibold">
                                                ✗ Afwijzen
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.referees.edit', $referee) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                            Bewerk
                                        </a>
                                    @endif
                                    <form action="{{ route('admin.referees.destroy', $referee) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Weet u zeker?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm">
                                            Verwijder
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $referees->links() }}
        </div>
    @else
        <div class="bg-gray-100 rounded-lg p-8 text-center">
            <p class="text-gray-600 text-lg">Geen scheidsrechtersaanmeldingen gevonden.</p>
        </div>
    @endif
</div>
@endsection
