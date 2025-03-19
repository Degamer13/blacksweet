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
    // Obtener todos los remates ordenados por fecha más reciente con paginación
    $remates = Remate::latest()->paginate(10);

    // Obtener los ejemplares agrupados por race_id
    $ejemplares = DB::table('ejemplar_race')
        ->select('race_id', 'ejemplar_name')
        ->get()
        ->groupBy('race_id');

    // Pasar los datos a la vista
    return view('remates.lista_remates', compact('remates', 'ejemplares'));
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
            'acumulado'      => 'required|array',
        ]);

        // Calcular los totales globales
        $total_subasta = array_sum($request->total);
        $porcentaje = $total_subasta * 0.30;
        $total_pagar = ($total_subasta - $porcentaje) + max($request->pote)+ max($request->acumulado);

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
                'acumulado'      => $request->acumulado[$index],
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
             // ACTUALIZAR EL STATUS EN LA TABLA ejemplar_race A 'desactivar'
        DB::table('ejemplar_race')
        ->where('race_id', $race_id)
        ->where('ejemplar_name', $request->ejemplar_name[$index]) // Filtrar también por el ejemplar
        ->update(['status' => 'desactivar']);
        }

        return redirect()->route('remates.logros_remates')->with('success', 'subasta_finalizada');
    }
    public function LogrosRemates()
    {
        // Obtener todos los remates ordenados por fecha más reciente con paginación
        $remates = Remate::latest()->paginate(10);
    
        // Obtener los ejemplares agrupados por race_id
        $ejemplares = DB::table('ejemplar_race')
            ->select('race_id', 'ejemplar_name')
            ->get()
            ->groupBy('race_id');
    
        // Pasar los datos a la vista
        return view('remates.logros_remates', compact('remates', 'ejemplares'));
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



  public function edit()
  {
    
  }



  public function update(Request $request, $id)
  {
      
  }




    public function destroy(Remate $remate)
    {
        $remate->delete();
        return redirect()->route('remates.index')->with('success', 'Remate eliminado.');
    }
}

