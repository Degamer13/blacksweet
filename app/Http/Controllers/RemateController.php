<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remate;
use App\Models\Race;
use App\Models\EjemplarRace; // Asegúrate de tener el modelo EjemplarRace
use App\Models\Ejemplar;
use Illuminate\Support\Facades\DB;

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
    // Filtrar carreras que tengan ejemplares con status 'activar'
    $races = Race::whereHas('ejemplars', function ($query) {
        $query->whereHas('ejemplarRaces', function ($query) {
            $query->where('status', 'activar');
        });
    })->get(); // Filtra carreras con ejemplares activos

    // Verificar si se encontraron carreras
    if ($races->isEmpty()) {
        return redirect()->route('remates.index')->with('error', 'No hay carreras activas con ejemplares disponibles.');
    }

    // Retornar la vista de edición pasando el remate y las carreras
    return view('remates.edit', compact('remate', 'races'));
}


public function update(Request $request, $id)
{
    // Validación de datos
    $request->validate([
        'number' => 'required|integer',
        'race_id' => 'required|exists:races,id',
        'ejemplar_id' => 'required|exists:ejemplars,id',
        'cliente' => 'required|string|max:255',
        'monto1' => 'required|numeric|min:0', // Validar que monto1 sea numérico y mayor o igual a 0
        'pote' => 'nullable|numeric|min:0' // Pote es opcional pero si se proporciona debe ser numérico y mayor o igual a 0
    ]);

    // Buscar el remate que se quiere actualizar
    $remate = Remate::findOrFail($id);

    // Obtener monto1
    $monto1 = $request->monto1;

    // Asegurar que los valores siempre terminen en 0
    $monto2 = ceil($monto1 / 2);
    $monto3 = ceil($monto2 / 2);
    $monto4 = ceil($monto3 / 2 / 5) * 5;

    // Actualizar el remate con los nuevos datos
    $remate->update([
        'number' => $request->number,
        'race_id' => $request->race_id,
        'ejemplar_id' => $request->ejemplar_id,
        'cliente' => $request->cliente,
        'monto1' => $monto1,
        'monto2' => $monto2,
        'monto3' => $monto3,
        'monto4' => $monto4,
        'pote' => $request->pote ?? 0 // Si el pote no se proporciona, asignar 0 por defecto
    ]);

    // Redirigir a una página con éxito
    return redirect()->route('remates.index')->with('success', 'Remate actualizado correctamente');
}
public function actualizarRemate(Request $request)
{
    // Validar que los datos necesarios estén presentes
    $request->validate([
        'race_id' => 'required|exists:races,id',
        'totalM1' => 'required|numeric',
        'totalM2' => 'required|numeric',
        'totalM3' => 'required|numeric',
        'totalM4' => 'required|numeric',
        'totalSubasta' => 'required|numeric',
        'porcentaje' => 'required|numeric',
        'pote' => 'nullable|numeric',
        'totalPagar' => 'required|numeric',
    ]);

    try {
        // Iniciar una transacción
        DB::beginTransaction();

        // Buscar todos los remates para la carrera correspondiente
        $remates = Remate::where('race_id', $request->race_id)->get();

        foreach ($remates as $remate) {
            // Actualizar los campos del remate
            $remate->update([
                'porcentaje' => $request->porcentaje,

                'total_pagar' => $request->totalPagar,
                'total_subasta' => $request->totalSubasta,
                'subasta1' => $request->totalM1,
                'subasta2' => $request->totalM2,
                'subasta3' => $request->totalM3,
                'subasta4' => $request->totalM4,
            ]);
        }

        // Confirmar la transacción
        DB::commit();

        return response()->json(['message' => 'Totales actualizados correctamente']);
    } catch (\Exception $e) {
        // Si ocurre algún error, revertir la transacción
        DB::rollBack();
        return response()->json(['message' => 'Ocurrió un error al actualizar los totales', 'error' => $e->getMessage()], 500);
    }
}


    public function destroy(Remate $remate)
    {
        $remate->delete();
        return redirect()->route('remates.index')->with('success', 'Remate eliminado.');
    }
}
