<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Services\PoolAllocationService;
use Illuminate\Http\Request;

class TournamentPoolController extends Controller
{
    protected $poolService;

    public function __construct(PoolAllocationService $poolService)
    {
        $this->poolService = $poolService;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Toon poule overzicht voor toernooi
     */
    public function index(Tournament $tournament)
    {
        $pools = $tournament->pools()->with('schools')->get();
        $pendingSchools = \App\Models\School::where('status', 'approved')
            ->whereNull('pool_id')
            ->count();

        return view('admin.pools.tournament_pools', compact('tournament', 'pools', 'pendingSchools'));
    }

    /**
     * Indeel goedgekeurde scholen automatisch in poules
     */
    public function allocateSchools(Tournament $tournament)
    {
        $result = $this->poolService->allocateSchoolsToPoolsAndCreateMatches($tournament);

        return redirect()->back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    /**
     * Maak wedstrijden aan voor alle poules
     */
    public function createMatches(Tournament $tournament)
    {
        $result = $this->poolService->checkAndCreateMatches($tournament);

        return redirect()->back()->with(
            $result['success'] ? 'success' : 'info',
            $result['message']
        );
    }

    /**
     * Verwijder alle poules en wedstrijden van toernooi (reset)
     */
    public function reset(Tournament $tournament)
    {
        \App\Models\GameMatch::where('tournament_id', $tournament->id)->delete();
        
        $tournament->pools()->each(function ($pool) {
            $pool->schools()->update(['pool_id' => null]);
            $pool->delete();
        });

        return redirect()->back()->with('success', 'Alle poules en wedstrijden zijn verwijderd.');
    }

    /**
     * Verwijder een specifieke poule
     */
    public function destroy(Tournament $tournament, $poolId)
    {
        $pool = $tournament->pools()->findOrFail($poolId);
        
        // Verwijder wedstrijden van deze poule
        \App\Models\GameMatch::where('tournament_id', $tournament->id)
            ->where(function ($query) use ($pool) {
                $schoolIds = $pool->schools()->pluck('id')->toArray();
                $query->whereIn('home_school_id', $schoolIds)
                      ->orWhereIn('away_school_id', $schoolIds);
            })
            ->delete();
        
        // Verwijder pool link van scholen
        $pool->schools()->update(['pool_id' => null]);
        
        // Verwijder poule
        $pool->delete();

        return redirect()->back()->with('success', 'Poule verwijderd en scholen vrijgegeven.');
    }
}
