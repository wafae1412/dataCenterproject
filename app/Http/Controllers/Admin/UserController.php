<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
     // show all users
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    // change user rule
    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
         if ($user->id === auth()->id()) {
        return back()->with('error', 'Vous pouvez pas changer votre role');
    }
        $user->role_id = $request->role_id;
        $user->save();

        return back()->with('success', 'Role modifié avec succès');
    }
     
public function create()
{
    $roles = Role::all();
    return view('admin.users.create', compact('roles'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'role_id' => 'required'
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $request->role_id
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'User تزاد بنجاح');
}
public function destroy($id)
{
    $user = User::findOrFail($id);

    // ❌ empecher l'admine de supp son role
    if ($user->id === auth()->id()) {
        return back()->with('error', 'Vous pouvez pas changer votre role');
    }

    $user->delete();

    return back()->with('success', 'User تحيد بنجاح');
}

}
