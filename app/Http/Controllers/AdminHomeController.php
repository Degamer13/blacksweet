<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/*use App\Models\User;
use App\Models\Cliente;
use App\Models\Proveedor;
*/

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
       /* $cantidadUsuarios = User::count();
        $cantidadClientes = Cliente::count();
        $cantidadProveedores = Proveedor::count();*/

        return view('adminhome');
    }
}
