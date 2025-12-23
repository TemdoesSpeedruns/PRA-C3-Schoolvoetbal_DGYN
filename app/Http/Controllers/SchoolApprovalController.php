<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SchoolRegistrationConfirmation;
use App\Models\School;
use App\Models\Pool;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SchoolApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        
        $query = School::orderBy('created_at', 'desc');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $schools = $query->paginate(20);

        return view('admin.schools.index', compact('schools', 'status'));
    }

    public function approve(int $id): RedirectResponse
    {
        $school = School::findOrFail($id);
        $school->status = 'approved';
        
        // Wijs automatisch aan een poule toe
        $this->assignToPool($school);
        
        $school->save();

        try {
            Mail::to($school->email)->send(new SchoolRegistrationConfirmation($school, 'approved'));
        } catch (\Exception $e) {
            report($e);
        }

        return back()->with('status', 'School "' . $school->name . '" is goedgekeurd en ingedeeld!');
    }

    private function assignToPool(School $school): void
    {
        // Zoek de actieve toernooien
        $tournaments = \App\Models\Tournament::where('status', 'active')->get();
        
        foreach ($tournaments as $tournament) {
            // Maak poules aan als ze nog niet bestaan (A, B, C, enz)
            $poolCount = Pool::where('tournament_id', $tournament->id)->count();
            
            if ($poolCount === 0) {
                // Maak standaard poule A aan
                Pool::create([
                    'tournament_id' => $tournament->id,
                    'name' => 'A',
                ]);
            }
            
            // Zoek de poule met de minste scholen
            $pools = Pool::where('tournament_id', $tournament->id)
                ->withCount('schools')
                ->orderBy('schools_count')
                ->get();
            
            if ($pools->count() > 0) {
                $poolToAdd = $pools->first();
                $schoolCount = $poolToAdd->schools()->count();
                
                // Als poule vol is (4 scholen), maak nieuwe poule aan
                if ($schoolCount >= 4) {
                    $lastPoolName = Pool::where('tournament_id', $tournament->id)
                        ->orderBy('name', 'desc')
                        ->first()
                        ->name;
                    
                    $nextName = chr(ord($lastPoolName) + 1);
                    
                    $poolToAdd = Pool::create([
                        'tournament_id' => $tournament->id,
                        'name' => $nextName,
                    ]);
                }
                
                $school->pool_id = $poolToAdd->id;
            }
        }
    }

    public function reject(int $id): RedirectResponse
    {
        $school = School::findOrFail($id);
        $school->status = 'rejected';
        $school->save();

        try {
            Mail::to($school->email)->send(new SchoolRegistrationConfirmation($school, 'rejected'));
        } catch (\Exception $e) {
            report($e);
        }

        return back()->with('status', 'School "' . $school->name . '" is afgewezen.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $school = School::findOrFail($id);
        $schoolName = $school->name;
        $school->delete();

        return back()->with('status', 'School "' . $schoolName . '" is verwijderd.');
    }

    public function edit(School $school): View
    {
        return view('admin.schools.edit', compact('school'));
    }

    public function update(Request $request, School $school): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:schools,name,' . $school->id,
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:schools,email,' . $school->id,
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $school->update($validated);

        return redirect()->route('admin.schools.index')->with('status', 'School "' . $school->name . '" is bijgewerkt!');
    }
}