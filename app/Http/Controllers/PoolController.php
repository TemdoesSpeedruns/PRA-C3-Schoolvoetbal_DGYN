<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\View\View;

class PoolController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View
    {
        $tournaments = Tournament::with(['pools' => function ($query) {
            $query->with('schools')->orderBy('name');
        }])
        ->where('status', 'active')
        ->orWhere('status', 'completed')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('admin.pools.index', compact('tournaments'));
    }
}
