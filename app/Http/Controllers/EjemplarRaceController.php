<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EjemplarRace;
use App\Models\Race;
use App\Models\Ejemplar;

class EjemplarRaceController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:parametro-list|parametro-create|parametro-edit|parametro-delete', ['only' => ['index','store']]);
        $this->middleware('permission:parametro-create', ['only' => ['create','store']]);
        $this->middleware('permission:parametro-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:parametro-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $search = $request->input('search');

        $races = Race::whereHas('ejemplarRaces')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            })
            ->paginate(5);

        return view('parametros.index', compact('races', 'search'));
    }

    public function create()
    {
        $races = Race::all();
        $ejemplars = Ejemplar::all();

        return view('parametros.create', compact('races', 'ejemplars'));
    }
public function store(Request $request)
{
    // Validación de las carreras y ejemplares
    $request->validate([
        'race_id' => 'required|array',
        'race_id.*' => 'exists:races,id',  // Asegúrate de que las carreras existan
        'ejemplars' => 'required|array',
        'ejemplars.*' => 'exists:ejemplars,id',  // Asegúrate de que los ejemplares existan
    ]);

    // Recorremos las carreras seleccionadas
    foreach ($request->race_id as $index => $raceIds) {
        // Recorremos los ejemplares seleccionados para cada carrera
        foreach ($request->ejemplars[$index] as $ejemplar_id) {
            // Crear la relación entre carrera y ejemplar con el status 'desactivado'
            EjemplarRace::create([
                'race_id' => $raceIds[0],  // Usamos el primer race_id, ya que puedes seleccionar solo uno por carrera
                'ejemplar_id' => $ejemplar_id,
                'status' => 'desactivar',  // Asignamos el estado desactivado por defecto
            ]);
        }
    }

    // Redirigimos al listado de carreras con un mensaje de éxito
    return redirect()->route('parametros.index')->with('success', 'Relaciones de carreras y ejemplares registradas correctamente.');
}


    public function show($race_id)
    {
        $race = Race::findOrFail($race_id);
        $ejemplars = EjemplarRace::where('race_id', $race_id)->with('ejemplar')->get();

        return view('parametros.show', compact('race', 'ejemplars'));
    }
   // Actualizar la relación de ejemplares y carreras en masa
// Método editMass
public function edit($race_id)
{
    // Obtener la carrera y los ejemplares asociados
    $race = Race::findOrFail($race_id);
    $races = Race::all(); // Todas las carreras para la selección
    $ejemplars = Ejemplar::all(); // Todos los ejemplares para la selección

    // Obtener los ejemplares que ya están asociados con esta carrera
    $selectedEjemplars = EjemplarRace::where('race_id', $race_id)->pluck('ejemplar_id')->toArray();

    // Obtener el estado actual de las relaciones de los ejemplares con la carrera
    $status = EjemplarRace::where('race_id', $race_id)
                ->whereIn('ejemplar_id', $selectedEjemplars)
                ->first()
                ->status ?? 'desactivar'; // Si no hay relación, asignar "desactivado"

    return view('parametros.edit', compact('race', 'races', 'ejemplars', 'selectedEjemplars', 'status'));
}


    // Actualizar las relaciones de carrera-ejemplar en masa
  public function update(Request $request, $id)
{
    // Validación de los datos
    $request->validate([
        'race_id' => 'required|exists:races,id',  // Validar que el race_id exista
        'ejemplars' => 'required|array',  // Aseguramos que haya ejemplares
        'ejemplars.*' => 'exists:ejemplars,id',  // Validar que los ejemplares existan
        'status' => 'required|in:activar,desactivar',  // Validar el status
    ]);

    // Obtener la carrera
    $race = Race::findOrFail($id);

    // Eliminar relaciones existentes
    EjemplarRace::where('race_id', $id)->delete();

    // Crear nuevas relaciones
    foreach ($request->ejemplars as $ejemplar_id) {
        EjemplarRace::create([
            'race_id' => $request->race_id,
            'ejemplar_id' => $ejemplar_id,
            'status' => $request->status,  // El status se asigna desde la vista
        ]);
    }

    // Redirigir al listado de carreras con mensaje de éxito
    return redirect()->route('parametros.index')->with('success', 'Carrera y ejemplares actualizados correctamente.');
}

   /* public function edit($id)
    {
        $ejemplarRace = EjemplarRace::findOrFail($id);
        $races = Race::all();
        $ejemplars = Ejemplar::whereHas('ejemplarRaces', function ($query) use ($ejemplarRace) {
            $query->where('race_id', $ejemplarRace->race_id);
        })->get();

        return view('parametros.edit', compact('ejemplarRace', 'races', 'ejemplars'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'race_id' => 'required|exists:races,id',
            'ejemplar_id' => 'required|exists:ejemplars,id',
            'status' => 'required|in:activo,desactivado',
        ]);

        $ejemplarRace = EjemplarRace::findOrFail($id);
        $ejemplarRace->update([
            'race_id' => $request->race_id,
            'ejemplar_id' => $request->ejemplar_id,
            'status' => $request->status,
        ]);

        return redirect()->route('parametros.index')->with('success', 'Relación carrera-ejemplar actualizada correctamente.');
    }*/
public function destroy($id)
{
    // Obtener la carrera con el id
    $race = Race::findOrFail($id);

    // Eliminar todas las relaciones en la tabla intermedia ejemplar_race para esa carrera
    $race->ejemplarRaces()->delete();

    return redirect()->route('parametros.index')->with('success', 'Relaciones de ejemplares eliminadas correctamente.');
}}
