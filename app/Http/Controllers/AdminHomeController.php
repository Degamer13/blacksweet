<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminHomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:show-admin', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $cantidadUsuarios = User::count();
        $cantidadRoles = Role::count(); // Contar roles

        return view('adminhome', compact('cantidadUsuarios', 'cantidadRoles'));
    }
}
