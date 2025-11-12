<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remate;
use App\Models\Race;
use App\Models\Bitacora;
use App\Models\EjemplarRace; // Asegúrate de tener el modelo EjemplarRace
use Illuminate\Support\Facades\DB;

class RemateController extends Controller
{

    function __construct()
{
    $this->middleware('permission:remate-list|remate-create|remate-edit|remate-delete', ['only' => ['index', 'store']]);
    $this->middleware('permission:remate-create', ['only' => ['create', 'store']]);
    $this->middleware('permission:remate-edit', ['only' => ['edit', 'update']]);
    $this->middleware('permission:remate-delete', ['only' => ['destroy']]);
    $this->middleware('permission:remate-show', ['only' => ['show']]);
    $this->middleware('permission:remate-search', ['only' => ['search']]);
}

    public function index(Request $request)
    {
        // Obtener los ejemplares agrupados por race_id donde el status sea 'activar'
        $ejemplares = DB::table('ejemplar_race')
            ->select('race_id', 'ejemplar_name', 'status')  // Seleccionamos race_id, ejemplar_name y status
            ->where('status', 'activar')  // Filtrar solo los que tengan el status 'activar'
            ->get()
            ->groupBy('race_id');  // Agrupar por race_id

        // Verificar si no hay ejemplares
        if ($ejemplares->isEmpty()) {
            return view('remates.index', ['ejemplares' => $ejemplares, 'noRecords' => true]);
        }

        // Pasar los ejemplares a la vista
        return view('remates.index', compact('ejemplares'));
    }
    public function listarRemates()
    {
        // Obtener los remates en el mismo orden que la base de datos
        $remates = Remate::orderBy('race_id')->orderBy('id')->get()->groupBy('race_id');

        // Obtener los ejemplares en el mismo orden que la base de datos
        $ejemplares = DB::table('ejemplar_race')
            ->select('race_id', 'ejemplar_name')
            ->orderBy('race_id') // Ordena por race_id primero
            ->orderBy('id') // Luego por id para mantener el orden de inserción
            ->get()
            ->groupBy('race_id');

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
    $total_pagar = ($total_subasta - $porcentaje) + max($request->pote) + max($request->acumulado);

    // Calcular los totales de cada subasta
    $subasta1 = array_sum($request->monto1);
    $subasta2 = array_sum($request->monto2);
    $subasta3 = array_sum($request->monto3);
    $subasta4 = array_sum($request->monto4);

    // Iterar cada fila enviada y guardar un remate
    foreach ($request->race_id as $index => $race_id) {
        $data = [
            'number'         => $index + 1,
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
            'created_at'     => now(),
            'updated_at'     => now(),
        ];

        // Guardar en la tabla remates
        Remate::create($data);

        // Guardar en la tabla bitacora
        DB::table('bitacora')->insert($data);

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
    // Obtener los remates en el mismo orden que la base de datos
    $remates = Remate::orderBy('race_id')->orderBy('id')->get()->groupBy('race_id');

    // Obtener los ejemplares en el mismo orden que la base de datos
    $ejemplares = DB::table('ejemplar_race')
        ->select('race_id', 'ejemplar_name')
        ->orderBy('race_id') // Ordena primero por race_id
        ->orderBy('id') // Luego por id para mantener el orden de inserción
        ->get()
        ->groupBy('race_id');

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

  public function editGlobal()
  {
      $ejemplares = DB::table('ejemplar_race')
          ->select('race_id', 'ejemplar_name')
          ->get()
          ->groupBy('race_id');

      $remates = Remate::all()->groupBy('race_id');

      return view('remates.edit', compact('ejemplares', 'remates'));


  }


public function updateGlobal(Request $request)
{
    $request->validate([
        'number'=> 'required|array',
        'race_id'        => 'required|array',
        'ejemplar_name'  => 'required|array',
        'cliente'        => 'nullable|array',
        'monto1'         => 'nullable|array',
        'monto2'         => 'nullable|array',
        'monto3'         => 'nullable|array',
        'monto4'         => 'nullable|array',
        'total'          => 'nullable|array',
        'pote'           => 'nullable|array',
        'acumulado'      => 'nullable|array',
    ]);

    // 1) Construir acumuladores por race_id
    $totalsByRace = [];

    foreach ($request->race_id as $index => $race_id) {
        $m1 = isset($request->monto1[$index]) ? floatval($request->monto1[$index]) : 0;
        $m2 = isset($request->monto2[$index]) ? floatval($request->monto2[$index]) : ($m1 / 2);
        $m3 = isset($request->monto3[$index]) ? floatval($request->monto3[$index]) : ($m2 / 2);
        $m4 = isset($request->monto4[$index]) ? floatval($request->monto4[$index]) : ($m3 / 2);
        $total = isset($request->total[$index]) ? floatval($request->total[$index]) : ($m1 + $m2 + $m3 + $m4);
        $pote = isset($request->pote[$index]) ? floatval($request->pote[$index]) : 0;
        $acumulado = isset($request->acumulado[$index]) ? floatval($request->acumulado[$index]) : 0;

        if (!isset($totalsByRace[$race_id])) {
            $totalsByRace[$race_id] = [
                'total_subasta' => 0.0,
                'subasta1' => 0.0,
                'subasta2' => 0.0,
                'subasta3' => 0.0,
                'subasta4' => 0.0,
                'max_pote' => $pote,
                'max_acumulado' => $acumulado,
            ];
        }

        $totalsByRace[$race_id]['total_subasta'] += $total;
        $totalsByRace[$race_id]['subasta1'] += $m1;
        $totalsByRace[$race_id]['subasta2'] += $m2;
        $totalsByRace[$race_id]['subasta3'] += $m3;
        $totalsByRace[$race_id]['subasta4'] += $m4;
        if ($pote > $totalsByRace[$race_id]['max_pote']) $totalsByRace[$race_id]['max_pote'] = $pote;
        if ($acumulado > $totalsByRace[$race_id]['max_acumulado']) $totalsByRace[$race_id]['max_acumulado'] = $acumulado;
    }

    // 2) Actualizar/crear cada remate usando los totales por carrera
    foreach ($request->race_id as $index => $race_id) {
        $raceTotals = $totalsByRace[$race_id];

        // Redondear para evitar discrepancias
        $total_subasta = round($raceTotals['total_subasta'], 2);
        $porcentaje = round($total_subasta * 0.30, 2);
        $total_pagar = round(($total_subasta - $porcentaje) + $raceTotals['max_pote'] + $raceTotals['max_acumulado'], 2);

        $data = [
            'number'         => $request->number[$index],
            'race_id'        => $race_id,
            'ejemplar_name'  => $request->ejemplar_name[$index],
            'cliente'        => $request->cliente[$index] ?? null,
            'monto1'         => $request->monto1[$index] ?? 0,
            'monto2'         => $request->monto2[$index] ?? 0,
            'monto3'         => $request->monto3[$index] ?? 0,
            'monto4'         => $request->monto4[$index] ?? 0,
            'total'          => $request->total[$index] ?? 0,
            'pote'           => $request->pote[$index] ?? 0,
            'acumulado'      => $request->acumulado[$index] ?? 0,
            // Totales por carrera
            'total_subasta'  => $total_subasta,
            'porcentaje'     => $porcentaje,
            'total_pagar'    => $total_pagar,
            'subasta1'       => round($raceTotals['subasta1'], 2),
            'subasta2'       => round($raceTotals['subasta2'], 2),
            'subasta3'       => round($raceTotals['subasta3'], 2),
            'subasta4'       => round($raceTotals['subasta4'], 2),
            'updated_at'     => now(),
        ];

        $remate = Remate::where('race_id', $race_id)
                        ->where('ejemplar_name', $request->ejemplar_name[$index])
                        ->first();

        if ($remate) {
            $remate->update($data);
        } else {
            $remate = Remate::create($data);
        }

        // Actualizar bitacora (usar los mismos valores por carrera)
        $bitacoraData = array_merge(
            [
                'race_id' => $race_id,
                'ejemplar_name' => $request->ejemplar_name[$index],
            ],
            $data
        );

        $existing = DB::table('bitacora')
                      ->where('race_id', $race_id)
                      ->where('ejemplar_name', $request->ejemplar_name[$index])
                      ->first();

        if ($existing) {
            DB::table('bitacora')
              ->where('race_id', $race_id)
              ->where('ejemplar_name', $request->ejemplar_name[$index])
              ->update($bitacoraData);
        } else {
            DB::table('bitacora')->insert($bitacoraData);
        }
    }

    return redirect()->route('remates.lista_remates')->with('success', 'Subasta actualizada');
}



public function destroy()
{

}

}

