<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total Users
        $totalUsers = User::count();

        // Als je Teams, Matches, Events modellen hebt kun je dit vullen
        $totalTeams = 0;      // vervang later
        $totalMatches = 0;    // vervang later
        $totalEvents = 0;     // vervang later

        // Recent users (laatste 5)
        $recentUsers = User::latest()->take(5)->get();

        return view('AdminDashboard', compact(
            'totalUsers',
            'totalTeams',
            'totalMatches',
            'totalEvents',
            'recentUsers'
        ));
    }
}
