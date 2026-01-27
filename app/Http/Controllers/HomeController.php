<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     public function index()
{
    $role = auth()->user()->role->name;

    if ($role === 'Admin') {
        return redirect('/admin/dashboard');
    }

    if ($role === 'Responsable') {
        return redirect('/responsable/dashboard');
    }

    if ($role === 'Guest') {
        return redirect('/dashboard');
    }

    // User par défaut
    return redirect('/dashboard');
}

}
