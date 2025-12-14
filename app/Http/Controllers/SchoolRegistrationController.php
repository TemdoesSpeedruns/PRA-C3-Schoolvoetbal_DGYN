<?php

namespace App\Http\Controllers;

use App\Mail\SchoolRegistrationConfirmation;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SchoolRegistrationController extends Controller
{
    public function showForm()
    {
        return view('schools.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:schools,email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
        ]);

        $school = School::create($data + ['status' => 'pending']);

        // Stuur bevestigingsmail direct na registratie
        try {
            Mail::to($school->email)->send(new SchoolRegistrationConfirmation($school));
        } catch (\Exception $e) {
            // Log het probleem, maar laat registratie slagen
            report($e);
        }

        return redirect()->route('schools.register.form')
            ->with('status', 'Registratie ontvangen. Je ontvangt direct een bevestigingsmail.');
    }
}