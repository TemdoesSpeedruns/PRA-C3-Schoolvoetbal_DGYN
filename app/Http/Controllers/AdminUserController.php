<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller  // <-- belangrijk
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);  // werkt nu
    }

    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function toggleAdmin(User $user)
    {
        if ($user->email === 'jouwemail@voorbeeld.com') {
            return redirect()->back()->with('error', 'De standaard admin kan niet worden verwijderd of zijn admin status verliezen.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        return redirect()->back()->with('success', 'Admin status aangepast!');
    }
}
