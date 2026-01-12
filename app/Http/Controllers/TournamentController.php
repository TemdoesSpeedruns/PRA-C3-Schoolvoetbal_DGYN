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

    public function create()
    {
        return view('admin.tournaments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Voetbal 3/4,Voetbal 5/6,Voetbal 7/8,Voetbal VO meisjes,Voetbal VO jongens/gemend,Lijnbal basisschool,Lijnbal VO',
            'year' => 'required|integer|min:2000|max:' . date('Y') + 1,
            'status' => 'required|in:active,completed',
        ]);

        $tournament = Tournament::create($validated);

        return redirect()->route('admin.tournaments.index')->with('status', 'Toernooi "' . $tournament->name . '" is succesvol aangemaakt!');
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
