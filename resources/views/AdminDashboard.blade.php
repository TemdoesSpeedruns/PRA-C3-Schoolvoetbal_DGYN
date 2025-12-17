@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="text-black">Admin Dashboard</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card bg-white border">
                <div class="card-body">
                    <h5 class="card-title text-black">Total Users</h5>
                    <p class="card-text display-4 text-black">{{ $totalUsers ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-white border">
                <div class="card-body">
                    <h5 class="card-title text-black text-white">Total Teams</h5>
                    <p class="card-text display-4 text-black ">{{ $totalTeams ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-white border">
                <div class="card-body">
                    <h5 class="card-title text-black">Total Matches</h5>
                    <p class="card-text display-4 text-black">{{ $totalMatches ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-white border">
                <div class="card-body">
                    <h5 class="card-title text-black">Total Events</h5>
                    <p class="card-text display-4 text-black">{{ $totalEvents ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card bg-whiteborder ">
                <div class="card-header text-black">
                    <h5>Recent Users</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm text-black">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers ?? [] as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2">No users found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

       

        <div class="col-md-6">
            <div class="card bg-white ">
                <div class="card-header text-black">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('manage.users') }}" class="btn btn-primary btn-block mb-2">Manage Users</a>
                    <a href="#" class="btn btn-primary btn-block mb-2">Manage Teams</a>
                    <a href="#" class="btn btn-primary btn-block mb-2">View Reports</a>
                    <a href="#" class="btn btn-primary btn-block">Settings</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
