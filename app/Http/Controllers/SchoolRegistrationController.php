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
        return view('admin.schools.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:schools,name',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:schools,email',
        ]);

        $school = School::create($data + ['status' => 'pending']);

        // Stuur bevestigingsmail direct na registratie
        try {
            Mail::to($school->email)->send(new SchoolRegistrationConfirmation($school));
        } catch (\Exception $e) {
            report($e);
        }

        return redirect()->route('schools.register.form')
            ->with('success', 'Bedankt voor uw registratie! De beheerder zal uw aanvraag binnenkort beoordelen.');
    }
}