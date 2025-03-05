<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:race-list|race-create|race-edit|race-delete', ['only' => ['index','store']]);
         $this->middleware('permission:race-create', ['only' => ['create','store']]);
         $this->middleware('permission:race-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:race-delete', ['only' => ['destroy']]);
    }

    // Mostrar todas las carreras
    public function index(Request $request)
    {    $buscarpor=$request->get('buscarpor');
        $races = Race::where('name','like','%'.$buscarpor.'%')->paginate(5);// Obtener todas las carreras
        return view('races.index',compact('races', 'buscarpor'));

    }

    // Mostrar el formulario para crear una nueva carrera
    public function create()
    {
        return view('races.create');
    }

    // Almacenar una nueva carrera
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', // Validación
        ]);

        Race::create([
            'name' => $request->name,
        ]);

        return redirect()->route('races.index')->with("success","Carrera registrada con éxito"); // Redirigir a la lista de carreras
    }

    // Mostrar una carrera específica
    public function show(Race $race)
    {
        return view('races.show', compact('race'));
    }

    // Mostrar el formulario para editar una carrera
    public function edit(Race $race)
    {
        return view('races.edit', compact('race'));
    }

    // Actualizar una carrera
    public function update(Request $request, Race $race)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $race->update([
            'name' => $request->name,
        ]);

        return redirect()->route('races.index')->with("success","Carrera actualizada con éxito"); // Redirigir a la lista de carreras
    }

    // Eliminar una carrera
    public function destroy(Race $race)
    {
        $race->delete();
        return redirect()->route('races.index')->with("success","Carrera eliminada con éxito"); // Redirigir a la lista de carreras
    }
}
