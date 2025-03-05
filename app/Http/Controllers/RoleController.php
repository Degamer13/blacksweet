<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $buscarpor=$request->get('buscarpor');
        $roles = Role::where('name','like','%'.$buscarpor.'%')->paginate(5);
        return view('roles.index',compact('roles', 'buscarpor'));

    }

    public function create()
    {
        //$permissions = Permission::get();
        $permissions=Permission::all()->pluck('name','id');
        return view('roles.create', compact('permissions'));
    }

   public function store(Request $request)
{
    $this->validate($request, [
        'name' => 'required|unique:roles,name',
        'permissions' => 'required|array',
    ]);

    $role = Role::create([
        'name' => $request->input('name'), 
        'guard_name' => 'web'
    ]);

    // Asegúrate de que los permisos se sincronicen correctamente
    $role->syncPermissions($request->input('permissions'));

    return redirect()->route('roles.index')
        ->with('success', 'Rol creado exitosamente con permisos.');
}
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    public function edit($id)
{
    $role = Role::find($id);
    $permissions = Permission::get();
    $rolePermissions = $role->permissions->pluck('id')->all();

    return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
}

public function update(Request $request, $id)
{
    $this->validate($request, [
        'name' => 'required',
        'permissions' => 'required|array',
    ]);

    $role = Role::find($id);
    $role->name = $request->input('name');
    $role->save();

    $permissions = Permission::whereIn('id', $request->input('permissions'))->get();
    $role->syncPermissions($permissions);

    return redirect()->route('roles.index')
        ->with('success', 'Rol actualizado exitosamente');
}

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado exitosamente');
    }
}
