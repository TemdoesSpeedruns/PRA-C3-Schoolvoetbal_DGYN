@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Pagina Titel -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-black">Admin Dashboard</h1>
    </div>

    <!-- Statistieken -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @php
            $stats = [
                ['label' => 'Total Users', 'value' => $totalUsers ?? 0],
                ['label' => 'Total Teams', 'value' => $totalTeams ?? 0],
                ['label' => 'Total Matches', 'value' => $totalMatches ?? 0],
                ['label' => 'Total Events', 'value' => $totalEvents ?? 0],
            ];
        @endphp

        @foreach($stats as $stat)
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                <h5 class="text-sm text-gray-600 mb-1">{{ $stat['label'] }}</h5>
                <p class="text-3xl font-bold text-black">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Recent Users -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="border-b border-gray-200 px-4 py-3">
                <h5 class="font-semibold text-black">Recent Users</h5>
            </div>
            <div class="p-4">
                <table class="table-auto w-full border-collapse border border-gray-200 text-black text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-2 py-1 text-left">Name</th>
                            <th class="border border-gray-300 px-2 py-1 text-left">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers ?? [] as $user)
                            <tr class="bg-white">
                                <td class="border border-gray-300 px-2 py-1">{{ $user->name }}</td>
                                <td class="border border-gray-300 px-2 py-1">{{ $user->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="border border-gray-300 px-2 py-1 text-center text-gray-500">
                                    No users found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> 

        <!-- Quick Actions -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="border-b border-gray-200 px-4 py-3">
                <h5 class="font-semibold text-black">Quick Actions</h5>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('manage.users') }}" 
                   class="flex items-center justify-center gap-2 bg-green-500 text-black py-2 rounded-lg font-medium hover:bg-green-600 transition">
                    <span>Manage Users</span>
                </a>

                <a href="{{ route('admin.schools.index') }}" 
                   class="flex items-center justify-center gap-2 bg-blue-500 text-black py-2 rounded-lg font-medium hover:bg-blue-600 transition">
                    <span>Manage Schools</span>
                </a>

                <a href="{{ route('admin.pools.index') }}" 
                   class="flex items-center justify-center gap-2 bg-yellow-500 text-black py-2 rounded-lg font-medium hover:bg-yellow-600 transition">
                    <span>View Pools</span>
                </a>

                <a href="{{ route('admin.tournaments.index') }}" 
                   class="flex items-center justify-center gap-2 bg-purple-500 text-black py-2 rounded-lg font-medium hover:bg-purple-600 transition">
                    <span>Manage Tournaments</span>
                </a>

            </div>
        </div> 

    </div>

</div>
@endsection
