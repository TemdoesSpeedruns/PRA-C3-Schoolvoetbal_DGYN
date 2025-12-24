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
        // Beschikbare categorieën
        $categories = ['3/4', '5/6', '7/8', 'brugklas'];
        
        // Zoek de actieve toernooien
        $tournaments = \App\Models\Tournament::where('status', 'active')->get();
        
        foreach ($tournaments as $tournament) {
            // Bepaal categorie: gebruik schoolcategorie of 'all' als niet ingesteld
            $category = $school->category ?? 'all';
            
            // Zoek of maak poule voor deze combinatie (tournament + category)
            $poolQuery = Pool::where('tournament_id', $tournament->id)
                ->where('category', $category)
                ->withCount('schools')
                ->orderBy('schools_count');
            
            $pool = $poolQuery->first();
            
            // Als geen poule bestaat, maak eerste poule (A) aan
            if (!$pool) {
                $pool = Pool::create([
                    'tournament_id' => $tournament->id,
                    'name' => 'A',
                    'category' => $category,
                ]);
            }
            
            // Controleer of poule vol is (max 4 scholen)
            $schoolCount = $pool->schools()->count();
            
            if ($schoolCount >= 4) {
                // Maak nieuwe poule aan (B, C, D, etc.)
                $lastPool = Pool::where('tournament_id', $tournament->id)
                    ->where('category', $category)
                    ->orderBy('name', 'desc')
                    ->first();
                
                $lastPoolName = $lastPool ? $lastPool->name : 'A';
                $nextName = chr(ord($lastPoolName) + 1);
                
                $pool = Pool::create([
                    'tournament_id' => $tournament->id,
                    'name' => $nextName,
                    'category' => $category,
                ]);
            }
            
            // Wijs school toe aan poule (altijd naar eerste toernooi op basis van eerste iteratie)
            if ($tournaments->first()->id === $tournament->id) {
                $school->pool_id = $pool->id;
            }
        }
        
        // Save na alle toewijzingen
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