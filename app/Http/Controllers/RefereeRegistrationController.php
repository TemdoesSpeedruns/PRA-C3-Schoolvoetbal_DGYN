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

        $referee = Referee::create($data + ['is_active' => true]);

        return redirect()->route('referees.register.form')
            ->with('success', 'Dank u wel voor uw aanmelding als scheidsrechter! U bent nu actief in het systeem.');
    }

    public function index()
    {
        $referees = Referee::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('admin.referees.index', compact('referees'));
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
