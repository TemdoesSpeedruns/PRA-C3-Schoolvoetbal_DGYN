@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-black mb-4">Manage Users</h1>

    @if(session('success'))
        <div class="bg-green-100  text-green-800  px-4 py-2 rounded mb-2">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-2">
            {{ session('error') }}
        </div>
    @endif

    <table class="table-auto w-full border-collapse border border-gray-200 text-black">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300  px-2 py-1 text-left">Name</th>
                <th class="border border-gray-300  px-2 py-1 text-left">Email</th>
                <th class="border border-gray-300  px-2 py-1 text-left">Admin</th>
                <th class="border border-gray-300  px-2 py-1 text-left">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="bg-white">
                <td class="border border-gray-300  px-2 py-1">{{ $user->name }}</td>
                <td class="border border-gray-300  px-2 py-1">{{ $user->email }}</td>
                <td class="border border-gray-300  px-2 py-1">{{ $user->is_admin ? 'Yes' : 'No' }}</td>
                <td class="border border-gray-300  px-2 py-1">
                    @if($user->email !== 'admin@example.com')
                    <form method="POST" action="{{ route('manage.users.toggleAdmin', $user) }}">
                        @csrf
                        <button type="submit" class="bg-blue-500  text-black px-2 py-1 rounded text-sm">
                            {{ $user->is_admin ? 'Revoke Admin' : 'Make Admin' }}
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
