<?php

namespace App\Http\Controllers;

use App\Models\Referee;
use Illuminate\Http\Request;

class RefereeRegistrationController extends Controller
{
    public function showForm()
    {
        return view('referees.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:referees,email',
            'type' => 'required|in:junior,senior,professional',
            'phone' => 'nullable|string|max:20',
            'experience' => 'nullable|integer|min:0|max:100',
        ]);

        // Status standaard op 'pending' tot admin het goedkeurt
        $referee = Referee::create($data + ['status' => 'pending', 'is_active' => false]);

        return redirect()->route('referees.register.form')
            ->with('success', 'Dank u wel voor uw aanmelding! Uw aanvraag wordt binnenkort beoordeeld door de beheerder.');
    }

    public function index()
    {
        $referees = Referee::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('admin.referees.index', compact('referees'));
    }

    public function approvals()
    {
        $status = request('status', 'pending');
        
        $query = Referee::orderBy('created_at', 'desc');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $referees = $query->paginate(20);
        
        return view('admin.referees.approvals', compact('referees', 'status'));
    }

    public function approve(Referee $referee)
    {
        $referee->status = 'approved';
        $referee->is_active = true;
        $referee->save();

        return back()->with('success', 'Scheidsrechter "' . $referee->name . '" is goedgekeurd!');
    }

    public function reject(Referee $referee)
    {
        $referee->status = 'rejected';
        $referee->is_active = false;
        $referee->save();

        return back()->with('success', 'Scheidsrechter "' . $referee->name . '" is afgewezen.');
    }

    public function edit(Referee $referee)
    {
        return view('admin.referees.edit', compact('referee'));
    }

    public function update(Request $request, Referee $referee)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:referees,email,' . $referee->id,
            'type' => 'required|in:junior,senior,professional',
            'phone' => 'nullable|string|max:20',
            'experience' => 'nullable|integer|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $referee->update($data);

        return redirect()->route('admin.referees.index')
            ->with('success', 'Scheidsrechter bijgewerkt.');
    }

    public function destroy(Referee $referee)
    {
        $referee->delete();
        return redirect()->route('admin.referees.index')
            ->with('success', 'Scheidsrechter verwijderd.');
    }
}
