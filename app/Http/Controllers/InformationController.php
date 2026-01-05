<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class InformationController extends Controller
{
    /**
     * Show the game rules page
     */
    public function rules(): View
    {
        return view('information.rules');
    }
}
