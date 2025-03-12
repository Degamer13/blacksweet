<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remate;
use App\Models\Race;
use App\Models\EjemplarRace; // Asegúrate de tener el modelo EjemplarRace
use Illuminate\Support\Facades\DB;

class RemateController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $raceId = $request->input('race_id'); // Captura el filtro por carrera

        // Obtener las carreras que tienen ejemplares asociados desde la tabla ejemplar_race
        $races = DB::table('ejemplar_race')
            ->join('races', 'ejemplar_race.race_id', '=', 'races.id')
            ->select('races.id', 'races.name') // Obtén el id y nombre de la carrera
            ->groupBy('races.id', 'races.name')
            ->get();

        // Consulta base de remates con filtro opcional de carrera
        $query = Remate::query();

        if ($raceId) {
            $query->where('race_id', $raceId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('race', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                })->orWhereHas('ejemplarRace', function ($query) use ($search) {
                    $query->where('ejemplar_name', 'like', "%$search%");
                });
            });
        }

        $remates = $query->paginate(10);

        return view('remates.index', compact('remates', 'search', 'raceId', 'races'));
    }



    // Mostrar formulario de creación de remate
    public function create()
{
    // Obtener carreras con ejemplares activos asociados desde la tabla ejemplar_race
    $races = Race::whereHas('ejemplarRaces', function ($query) {
        $query->where('status', 'activar'); // Filtra solo los ejemplares activos
    })->get();

    return view('remates.create', compact('races'));
}


    // Guardar un nuevo remate
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'number' => 'required|integer',
            'race_id' => 'required|exists:races,id',
            'ejemplar_name' => 'required|string|max:255',
            'cliente' => 'required|string|max:255',
            'monto1' => 'required|numeric',
            'pote' => 'nullable|numeric'
        ]);

        // Calcular montos automáticamente
        $monto1 = $request->monto1;
        $monto2 = ceil($monto1 / 2);
        $monto3 = ceil($monto2 / 2);
        $monto4 = ceil($monto3 / 2 / 5) * 5;

        $total = $monto1 + $monto2 + $monto3 + $monto4;

        // Crear remate
        Remate::create([
            'number' => $request->number,
            'race_id' => $request->race_id,
            'ejemplar_name' => $request->ejemplar_name, // Usar ejemplar_name
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
  // Cargar los ejemplares activos de la carrera
  public function getEjemplarsByRace($raceId)
  {
      // Obtener los ejemplares relacionados con la carrera y cuyo estado sea 'activar'
      $ejemplars = EjemplarRace::where('race_id', $raceId)
          ->where('status', 'activar') // Solo los activos
          ->get();

      // Devolver solo el nombre del ejemplar desde la tabla intermedia
      $data = $ejemplars->map(function ($ejemplarRace) {
          return [
              'id' => $ejemplarRace->id,
              'name' => $ejemplarRace->ejemplar_name, // Usamos el campo ejemplar_name
          ];
      });

      return response()->json($data); // Enviar los ejemplares como JSON
  }

  // Validar si el ejemplar ya está registrado en los remates
  public function validarEjemplar($ejemplarId)
  {
      $existe = Remate::where('ejemplar_name', $ejemplarId)->exists();

      if ($existe) {
          return response()->json(['message' => '❌ Ya se encuentra registrado el ejemplar'], 400);
      } else {
          return response()->json(['message' => '✅ Felicidades, el ejemplar está disponible'], 200);
      }
  }


  public function edit(Remate $remate)
  {
      // Filtrar carreras que tengan ejemplares con status 'activar' en la tabla ejemplar_race
      $races = Race::whereHas('ejemplarRaces', function ($query) {
          $query->where('status', 'activar'); // Filtra solo los ejemplares activos
      })->get();

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
          'ejemplar_name' => 'required|string|max:255', // Cambiado para reflejar 'ejemplar_name'
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
          'ejemplar_name' => $request->ejemplar_name, // Usamos ejemplar_name en lugar de ejemplar_id
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

