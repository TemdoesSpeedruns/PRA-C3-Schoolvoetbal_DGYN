<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PublicPoolController extends Controller
{
    public function myPool(): View
    {
        // Probeer school te vinden via:
        // 1. school_id in sessie
        // 2. User's school relatie (als ingesteld)
        // 3. School naam uit ingelogde user
        
        $school = null;
        
        // Via session
        if (session('school_id')) {
            $school = School::find(session('school_id'));
        }
        
        // Via User (als er een school_id kolom is op users tabel)
        if (!$school && Auth::check() && Auth::user()->school_id) {
            $school = School::find(Auth::user()->school_id);
        }
        
        // Via User naam (fallback - zoek school op contact_person of email)
        if (!$school && Auth::check()) {
            $school = School::where('email', Auth::user()->email)->first();
        }
        
        return view('my-pool', compact('school'));
    }
}
