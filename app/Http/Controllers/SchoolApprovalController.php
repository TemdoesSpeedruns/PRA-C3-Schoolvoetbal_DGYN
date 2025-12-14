<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SchoolRegistrationConfirmation;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SchoolApprovalController extends Controller
{
    public function __construct()
    {
        // Zorg dat alleen geauthenticeerde admins bij deze controller kunnen
        $this->middleware(['auth', 'is_admin']);
    }

    public function index(Request $request): View
    {
        $schools = School::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.schools.index', compact('schools'));
    }

    public function approve(int $id): RedirectResponse
    {
        $school = School::findOrFail($id);
        $school->status = 'approved';
        $school->save();

        // Optioneel: mail de coach dat account is goedgekeurd
        try {
            Mail::to($school->email)->send(new SchoolRegistrationConfirmation($school, 'approved'));
        } catch (\Exception $e) {
            report($e);
        }

        return back()->with('status', 'School account goedgekeurd.');
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

        return back()->with('status', 'School account afgewezen.');
    }
}