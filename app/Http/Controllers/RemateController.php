<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remate;
use App\Models\Race;
use App\Models\EjemplarRace; // Asegúrate de tener el modelo EjemplarRace
use App\Models\Ejemplar;

class RemateController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    $raceId = $request->input('race_id'); // Captura el filtro por carrera

    // Obtener solo las carreras que tienen ejemplares asociados
    $races = Race::whereHas('ejemplars')->get();

    // Consulta base de remates con filtro opcional de carrera
    $query = Remate::query();

    if ($raceId) {
        $query->where('race_id', $raceId);
    }

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->whereHas('race', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })->orWhereHas('ejemplar', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            });
        });
    }

    $remates = $query->paginate(10);

    return view('remates.index', compact('remates', 'search', 'raceId', 'races'));
}


    // Mostrar formulario de creación de remate
     public function create()
    {
        $races = Race::whereHas('ejemplars', function ($query) {
            $query->whereHas('ejemplarRaces', function ($query) {
                $query->where('status', 'activar');
            });
        })->get(); // Filtra carreras que tengan ejemplares con status 'activar'

        return view('remates.create', compact('races'));
    }

    // Cargar los ejemplares activos para una carrera específica
    public function getEjemplarsByRace($race_id)
    {
        // Obtén los ejemplares relacionados con la carrera y cuyo status sea 'activar'
        $ejemplars = EjemplarRace::where('race_id', $race_id)
            ->where('status', 'activar')
            ->with('ejemplar') // Relacionar con el modelo Ejemplar para obtener los datos de los ejemplares
            ->get()
            ->map(function ($ejemplarRace) {
                return $ejemplarRace->ejemplar; // Devolver solo los ejemplares
            });

        return response()->json($ejemplars); // Responder con los ejemplares en formato JSON
    }

    // Guardar un nuevo remate

public function store(Request $request)
{
    // Validación de datos
    $request->validate([
        'number' => 'required|integer',
        'race_id' => 'required|exists:races,id',
        'ejemplar_id' => 'required|exists:ejemplars,id',
        'cliente' => 'required|string|max:255',
        'monto1' => 'required|numeric',
        'pote' => 'nullable|numeric'
    ]);

  // Calcular montos automáticamente
$monto1 = $request->monto1;

// Asegurar que los valores siempre terminen en 0
$monto2 = ceil($monto1 / 2 );
$monto3 = ceil($monto2 / 2 );
$monto4 = ceil($monto3 / 2 / 5) * 5;


$total = $monto1 + $monto2 + $monto3 + $monto4;


    // Crear remate con valores en 0
    Remate::create([
        'number' => $request->number,
        'race_id' => $request->race_id,
        'ejemplar_id' => $request->ejemplar_id,
        'cliente' => $request->cliente,
        'monto1' => $monto1,
        'monto2' => $monto2,
        'monto3' => $monto3,
        'monto4' => $monto4,
        'total' => $total,
        'porcentaje' => 0,
        'pote' => $request->pote ?? 0,
        'total_pagar' => 0,
        'total_subasta' => 0,
        'subasta1' => 0,
        'subasta2' => 0,
        'subasta3' => 0,
        'subasta4' => 0
    ]);

    return redirect()->route('remates.index')->with('success', 'Remate registrado con éxito');
}
public function validarEjemplar($ejemplar_id)
{
    $existe = Remate::where('ejemplar_id', $ejemplar_id)->exists();

    if ($existe) {
        return response()->json(['message' => '❌ Ya se encuentra registrado el ejemplar'], 400);
    } else {
        return response()->json(['message' => '✅ Felicidades, el ejemplar está disponible'], 200);
    }
}

    public function edit(Remate $remate)
    {
        $races = Race::all();
        return view('remates.edit', compact('remate', 'races'));
    }

    public function update(Request $request, Remate $remate)
    {
        $request->validate([
            'number' => 'required|integer',
            'race_id' => 'required|exists:races,id',
            'ejemplar_id' => 'required|exists:ejemplars,id|unique:remates,ejemplar_id,' . $remate->id,
            'cliente' => 'required|string',
        ]);

        $remate->update($request->all());

        return redirect()->route('remates.index')->with('success', 'Remate actualizado.');
    }

    public function destroy(Remate $remate)
    {
        $remate->delete();
        return redirect()->route('remates.index')->with('success', 'Remate eliminado.');
    }
}
