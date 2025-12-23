<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        
        $query = Tournament::orderBy('year', 'desc');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $tournaments = $query->get();

        return view('admin.tournaments.index', compact('tournaments', 'status'));
    }

    public function edit(Tournament $tournament)
    {
        return view('admin.tournaments.edit', compact('tournament'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'winner' => 'nullable|string|max:255',
            'status' => 'required|in:active,completed',
        ]);

        $tournament->update($validated);

        return redirect()->route('admin.tournaments.index')->with('status', 'Toernooi "' . $tournament->name . '" is bijgewerkt!');
    }
}
