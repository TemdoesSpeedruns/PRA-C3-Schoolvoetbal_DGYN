<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\Tournament;
use App\Models\School;
use App\Models\Referee;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    // Admin maakt wedstrijden aan
    public function createMatch()
    {
        $tournaments = Tournament::all();
        $schools = School::where('status', 'approved')->get();
        return view('admin.scores.create-match', compact('tournaments', 'schools'));
    }

    public function storeMatch(Request $request)
    {
        $validated = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'home_school_id' => 'required|exists:schools,id|different:away_school_id',
            'away_school_id' => 'required|exists:schools,id',
            'match_date' => 'required|regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Converteer van "2025-12-10T03:50" naar "2025-12-10 03:50"
        $matchDateTime = str_replace('T', ' ', $validated['match_date']);

        GameMatch::create([
            'tournament_id' => $validated['tournament_id'],
            'home_school_id' => $validated['home_school_id'],
            'away_school_id' => $validated['away_school_id'],
            'match_date' => $matchDateTime,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.scores.index')->with('success', 'Wedstrijd aangemaakt.');
    }

    // Admin kan scores invoeren
    public function index()
    {
        $matches = GameMatch::with(['tournament', 'homeSchool', 'awaySchool', 'referee'])
            ->orderBy('match_date', 'desc')
            ->get();
        return view('admin.scores.index', compact('matches'));
    }

    public function edit(GameMatch $match)
    {
        $referees = Referee::where('is_active', true)
            ->orderBy('name')
            ->get();
        return view('admin.scores.edit', compact('match', 'referees'));
    }

    public function update(Request $request, GameMatch $match)
    {
        $validated = $request->validate([
            'home_goals' => 'required|integer|min:0|max:99',
            'away_goals' => 'required|integer|min:0|max:99',
            'status' => 'required|in:scheduled,live,completed',
            'referee_id' => 'nullable|exists:referees,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $match->update($validated);
        return redirect()->route('admin.scores.index')->with('success', 'Score opgeslagen.');
    }

    public function destroy(GameMatch $match)
    {
        if ($match->status !== 'completed') {
            return back()->with('error', 'Je kunt alleen voltooide wedstrijden verwijderen.');
        }

        $match->delete();
        return back()->with('success', 'Wedstrijd verwijderd.');
    }

    // Publieke weergave van lopende/voltooide wedstrijden
    public function viewPublic()
    {
        $completedMatches = GameMatch::where('status', 'completed')
            ->with(['tournament', 'homeSchool', 'awaySchool'])
            ->orderBy('match_date', 'desc')
            ->get();

        $liveMatches = GameMatch::where('status', 'live')
            ->with(['tournament', 'homeSchool', 'awaySchool'])
            ->get();

        return view('scores', compact('completedMatches', 'liveMatches'));
    }
}
