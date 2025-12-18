@extends('layouts.app')

@section('content')
<div class="container mx-auto">

    <h1 class="text-xl font-semibold mb-4 text-black">School Aanvragen</h1>

    @if(session('status'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('status') }}
        </div>
    @endif

    <table class="table-auto w-full border-collapse border border-gray-200 text-black">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 px-2 py-1 text-left">#</th>
                <th class="border border-gray-300 px-2 py-1 text-left">School</th>
                <th class="border border-gray-300 px-2 py-1 text-left">Contact</th>
                <th class="border border-gray-300 px-2 py-1 text-left">Email</th>
                <th class="border border-gray-300 px-2 py-1 text-left">Status</th>
                <th class="border border-gray-300 px-2 py-1 text-left">Ingediend</th>
                <th class="border border-gray-300 px-2 py-1 text-left">Acties</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schools as $school)
                <tr class="bg-white">
                    <td class="border border-gray-300 px-2 py-1">{{ $school->id }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $school->name }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $school->contact_person }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $school->email }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ ucfirst($school->status) }}</td>
                    <td class="border border-gray-300 px-2 py-1">
                        {{ $school->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td class="border border-gray-300 px-2 py-1">
                        @if($school->status === 'pending')
                            <form method="POST"
                                  action="{{ route('admin.schools.approve', $school->id) }}"
                                  class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-green-500 text-black px-2 py-1 rounded text-sm mr-1">
                                    Goedkeuren
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('admin.schools.reject', $school->id) }}"
                                  class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-red-500 text-black px-2 py-1 rounded text-sm">
                                    Afwijzen
                                </button>
                            </form>
                        @else
                            <span class="text-gray-500 text-sm">
                                Geen acties beschikbaar
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $schools->links() }}
    </div>

</div>
@endsection
