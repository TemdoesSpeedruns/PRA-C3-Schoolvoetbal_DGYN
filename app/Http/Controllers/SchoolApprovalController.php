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
        
        // Save gebeurt IN assignToPool, niet hier

        try {
            Mail::to($school->email)->send(new SchoolRegistrationConfirmation($school, 'approved'));
        } catch (\Exception $e) {
            report($e);
        }

        return back()->with('status', 'School "' . $school->name . '" is goedgekeurd en ingedeeld!');
    }

    private function assignToPool(School $school): void
    {
        // Zoek de EERSTE actieve toernooi (simpel)
        $tournament = \App\Models\Tournament::where('status', 'active')->first();
        
        if (!$tournament) {
            return; // Geen actieve toernooi = geen toewijzing
        }
        
        // Stap 1: Vind alle poules voor dit toernooi
        $pools = Pool::where('tournament_id', $tournament->id)
            ->withCount('schools')
            ->orderBy('schools_count', 'asc')
            ->get();
        
        // Stap 2: Vind poule met minst scholen (en niet vol)
        $availablePool = $pools->firstWhere('schools_count', '<', 4);
        
        // Stap 3: Maak poule aan als nodig
        if (!$availablePool) {
            // Bepaal naam: A, B, C, D, etc.
            $existingCount = $pools->count();
            $poolName = chr(65 + $existingCount); // A=65, B=66, etc.
            
            $availablePool = Pool::create([
                'tournament_id' => $tournament->id,
                'name' => $poolName,
            ]);
        }
        
        // Stap 4: Wijs school toe
        $school->pool_id = $availablePool->id;
        $school->save();
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
            'category' => 'nullable|in:3/4,5/6,7/8,brugklas',
        ]);

        $school->update($validated);

        return redirect()->route('admin.schools.index')->with('status', 'School "' . $school->name . '" is bijgewerkt!');
    }
}