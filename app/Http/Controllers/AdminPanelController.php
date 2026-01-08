<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\School;
use App\Models\Pool;
use App\Models\User;
use App\Models\GameMatch;
use App\Models\Field;
use App\Models\Referee;
use Illuminate\View\View;

class AdminPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View
    {
        // Verzamel alle data voor het admin panel
        $tournaments = Tournament::with(['pools.schools'])->orderBy('created_at', 'desc')->get();
        $schools = School::orderBy('created_at', 'desc')->paginate(10);
        $matches = GameMatch::with(['tournament', 'homeSchool', 'awaySchool'])->orderBy('created_at', 'desc')->paginate(5);
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        $fields = Field::where('is_active', true)->get();
        $referees = Referee::where('is_active', true)->get();
        
        // Referees data
        $pending_referees = Referee::where('status', 'pending')->orderBy('created_at', 'desc')->get();
        $active_referees = Referee::where('status', 'approved')->where('is_active', true)->orderBy('name')->get();
        
        // Statistics
        $stats = [
            'total_schools' => School::count(),
            'approved_schools' => School::where('status', 'approved')->count(),
            'pending_schools' => School::where('status', 'pending')->count(),
            'rejected_schools' => School::where('status', 'rejected')->count(),
            'total_tournaments' => Tournament::count(),
            'active_tournaments' => Tournament::where('status', 'active')->count(),
            'total_matches' => GameMatch::count(),
            'scheduled_matches' => GameMatch::whereNotNull('scheduled_time')->count(),
            'total_users' => User::count(),
            'admin_users' => User::where('is_admin', true)->count(),
        ];

        return view('admin.panel', compact(
            'tournaments',
            'schools',
            'matches',
            'users',
            'fields',
            'referees',
            'pending_referees',
            'active_referees',
            'stats'
        ));
    }
}
