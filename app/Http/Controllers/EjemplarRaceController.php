<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EjemplarRace;
use App\Models\Race;
use App\Models\Remate;

class EjemplarRaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:parametro-list|parametro-create|parametro-edit|parametro-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:parametro-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:parametro-edit', ['only' => ['edit', 'update']]);
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
    public function toggleStatus($race_id)
{
    $race = Race::findOrFail($race_id);

    // Verifica si hay ejemplares activos en la carrera
    $hasActiveEjemplars = $race->ejemplarRaces()->where('status', 'activar')->exists();

    if ($hasActiveEjemplars) {
        // Si hay ejemplares activos, desactívalos todos
        $race->ejemplarRaces()->update(['status' => 'desactivar']);
    } else {
        // Si están desactivados, actívalos todos
        $race->ejemplarRaces()->update(['status' => 'activar']);
    }

    return redirect()->route('parametros.index')->with('success', 'Estado actualizado correctamente.');
}

    public function create()
    {
        $races = Race::all();
        return view('parametros.create', compact('races'));
    }

    public function store(Request $request)
{
    $request->validate([
        'race_id' => 'required|array',
        'race_id.*' => 'exists:races,id',
        'ejemplares' => 'required|array',
        'ejemplares.*' => 'nullable|string' // Recibimos los ejemplares como JSON
    ]);

    try {
        foreach ($request->race_id as $index => $raceId) {
            if (!isset($request->ejemplares[$index]) || empty($request->ejemplares[$index])) {
                continue; // Evita errores si no hay ejemplares
            }

            // Decodificar JSON
            $ejemplares = json_decode($request->ejemplares[$index], true);

            if (!is_array($ejemplares)) {
                continue; // Evita errores si la decodificación falla
            }

            foreach ($ejemplares as $ejemplarName) {
                EjemplarRace::create([
                    'race_id' => $raceId,
                    'ejemplar_name' => $ejemplarName,
                    'status' => 'desactivar',
                ]);
            }
        }

        return redirect()->route('parametros.index')->with('success', 'Registros guardados correctamente.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error al guardar: ' . $e->getMessage());
    }
}


    public function show($race_id)
    {
        $race = Race::findOrFail($race_id);
        return view('parametros.show', compact('race'));
    }

    public function edit($race_id)
    {
        // Buscar la carrera
        $race = Race::findOrFail($race_id);

        // Obtener todas las carreras
        $races = Race::all();

        // Obtener los ejemplares relacionados con esta carrera
        $ejemplars = EjemplarRace::where('race_id', $race_id)->get(); // Traemos todos los ejemplares relacionados

        // Obtener el estado de los ejemplares relacionados con la carrera
        $status = $ejemplars->first()->status ?? 'desactivar'; // Asumimos que todos los ejemplares tienen el mismo estado

        return view('parametros.edit', compact('race', 'races', 'ejemplars', 'status'));
    }




    public function update(Request $request, $race_id)
    {
        // Validar los datos del formulario
        $request->validate([
            'race_id' => 'required|exists:races,id',
            'ejemplar_name' => 'required|array', // Validar que ejemplar_name sea un arreglo
            'ejemplar_name.*' => 'required|string', // Validar que los nombres de los ejemplares sean cadenas
            'status' => 'required|in:activar,desactivar',
        ]);

        // Desactivar los ejemplares previos de la carrera
        EjemplarRace::where('race_id', $race_id)->update(['status' => 'desactivar']);

        // Luego, actualizar o crear los ejemplares seleccionados
        foreach ($request->ejemplar_name as $ejemplarId => $ejemplarName) {
            // Actualizar o crear el ejemplar
            EjemplarRace::updateOrCreate(
                ['race_id' => $race_id, 'id' => $ejemplarId], // Buscar por el ID del ejemplar
                ['ejemplar_name' => $ejemplarName, 'status' => $request->status] // Actualizar el nombre y el estado
            );
        }

        return redirect()->route('parametros.index')->with('success', 'Actualización exitosa.');
    }


    public function destroy($race_id)
    {
        EjemplarRace::where('race_id', $race_id)->delete();

        return redirect()->route('parametros.index')->with('success', 'La carrera y los ejemplares han sido eliminados');
    }

    public function destroyAll()
    {
        EjemplarRace::truncate(); // Elimina todos los registros y reinicia los IDs autoincrementables.
        Remate::truncate(); // Elimina todos los registros de la tabla 'remates'.
    
        return redirect()->route('parametros.index')->with('success', 'Todos los registros han sido eliminados.');
    }
    

}
