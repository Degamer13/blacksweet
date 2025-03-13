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
        // Obtener los ejemplares agrupados por race_id donde el status sea 'activar'
        $ejemplares = DB::table('ejemplar_race')
            ->select('race_id', 'ejemplar_name', 'status')  // Seleccionamos race_id, ejemplar_name y status
            ->where('status', 'activar')  // Filtrar solo los que tengan el status 'activar'
            ->get()
            ->groupBy('race_id');  // Agrupar por race_id

        // Pasar los ejemplares a la vista
        return view('remates.index', compact('ejemplares'));
    }
    public function listarRemates()
    {
        // Obtener todos los remates para la vista
        $remates = Remate::latest()->paginate(10);
    
        return view('remates.lista_remates', compact('remates'));
    }
    // Guardar un nuevo remate
    public function store(Request $request)
    {
        // Validar datos recibidos
        $request->validate([
            'race_id'        => 'required|array',
            'ejemplar_name'  => 'required|array',
            'cliente'        => 'required|array',
            'monto1'         => 'required|array',
            'monto2'         => 'required|array',
            'monto3'         => 'required|array',
            'monto4'         => 'required|array',
            'total'          => 'required|array',
            'pote'           => 'required|array',
        ]);
    
        // Calcular los totales globales
        $total_subasta = array_sum($request->total);
        $porcentaje = $total_subasta * 0.30;
        $total_pagar = ($total_subasta - $porcentaje) + max($request->pote);
        
        // Calcular los totales de cada subasta
        $subasta1 = array_sum($request->monto1);
        $subasta2 = array_sum($request->monto2);
        $subasta3 = array_sum($request->monto3);
        $subasta4 = array_sum($request->monto4);
    
        // Iterar cada fila enviada y guardar un remate
        foreach ($request->race_id as $index => $race_id) {
            Remate::create([
                'number'         => $index + 1, // o algún otro criterio para el número
                'race_id'        => $race_id,
                'ejemplar_name'  => $request->ejemplar_name[$index],
                'cliente'        => $request->cliente[$index],
                'monto1'         => $request->monto1[$index],
                'monto2'         => $request->monto2[$index],
                'monto3'         => $request->monto3[$index],
                'monto4'         => $request->monto4[$index],
                'total'          => $request->total[$index],
                'pote'           => $request->pote[$index],
                // Totales globales
                'total_subasta'  => $total_subasta,
                'porcentaje'     => $porcentaje,
                'total_pagar'    => $total_pagar,
                // Totales individuales de subastas
                'subasta1'       => $subasta1,
                'subasta2'       => $subasta2,
                'subasta3'       => $subasta3,
                'subasta4'       => $subasta4,
            ]);
        }
    
        return redirect()->route('remates.index')->with('success', 'subasta_finalizada');
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

