<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;

class ResultController extends Controller
{
    // Laatste 10 uitslagen
    public function index()
    {
        $results = Result::orderBy('date', 'desc')->limit(10)->get();
        return view('results.index', compact('results'));
    }

    // Historie
    public function history()
    {
        $results = Result::orderBy('date', 'desc')->get();
        return view('results.history', compact('results'));
    }

    // Formulier tonen
    public function create()
    {
        return view('results.create');
    }

    // Opslaan
    public function store(Request $request)
    {
        $request->validate([
            'tournament_name' => 'required|string|max:255',
            'winner_name' => 'required|string|max:255',
            'runner_up' => 'nullable|string|max:255',
            'date' => 'required|date'
        ]);

        Result::create([
            'tournament_name' => $request->tournament_name,
            'winner_name' => $request->winner_name,
            'runner_up' => $request->runner_up,
            'date' => $request->date
        ]);

        return redirect('/uitslagen');
    }
}
