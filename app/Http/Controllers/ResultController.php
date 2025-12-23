<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;

class ResultController extends Controller
{
    // Laatste uitslagen / current results
    public function index()
    {
        $tournaments = Tournament::where('status', 'active')->get();
        return view('results', compact('tournaments')); // resources/views/results.blade.php
    }

    // Historie / previous winners
    public function history()
    {
        $tournaments = Tournament::whereNotNull('winner')->orderBy('year', 'desc')->get();
        return view('historie', compact('tournaments')); // resources/views/historie.blade.php
    }
}
