<?php

namespace App\Http\Controllers;

use App\Models\Ganador;
use App\Models\Remate;
use App\Models\GanadorBitacora; // importar el modelo de la bitácora
use Illuminate\Http\Request;
use PDF; // alias de Dompdf en config/app.php o use Barryvdh\DomPDF\Facade\Pdf;

class GanadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ganador-list|ganador-create|ganador-edit|ganador-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:ganador-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:ganador-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:ganador-delete', ['only' => ['destroy']]);
        $this->middleware('permission:ganador-show', ['only' => ['show']]);
        $this->middleware('permission:ganador-search', ['only' => ['search']]);
    }

    /** Mostrar todos los ganadores */
    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');

        $ganadores = Ganador::with('remate')
            ->when($buscarpor, function ($query) use ($buscarpor) {
                $query->whereHas('remate', function ($q) use ($buscarpor) {
                    $q->where('cliente', 'like', '%' . $buscarpor . '%')
                      ->orWhere('ejemplar_name', 'like', '%' . $buscarpor . '%');
                });
            })
            ->paginate(5);

        return view('ganadores.index', compact('ganadores', 'buscarpor'));
    }

    /** Formulario para crear un ganador */
    public function create()
    {
        $remates = Remate::all();
        return view('ganadores.create', compact('remates'));
    }

    /** Guardar ganadores con manejo de empates y bitácora */
    public function store(Request $request)
    {
        $request->validate([
            'remate_ids' => 'required|array',
            'remate_ids.*' => 'exists:remates,id',
            'es_ganador' => 'required|boolean',
            'posicion' => 'nullable|integer',
        ]);

        $remateIds = $request->remate_ids;
        $totalGanadores = count($remateIds);

        foreach ($remateIds as $remateId) {
            $remate = Remate::findOrFail($remateId);

            $montoPorGanador = $request->es_ganador ? $remate->total_pagar / $totalGanadores : 0;
            $porcentaje = $request->es_ganador ? 100 / $totalGanadores : 0;

            // 1️⃣ Crear el ganador
            $ganador = Ganador::create([
                'remate_id'    => $remateId,
                'es_ganador'   => $request->es_ganador,
                'posicion'     => $request->posicion,
                'monto_ganado' => $montoPorGanador,
                'porcentaje'   => $porcentaje,
                'total_pagar'  => $remate->total_pagar,
            ]);

            // 2️⃣ Guardar en la bitácora
            GanadorBitacora::create([
                'ganador_id'   => $ganador->id,
                'remate_id'    => $remateId,
                'es_ganador'   => $request->es_ganador,
                'posicion'     => $request->posicion,
                'monto_ganado' => $montoPorGanador,
                'porcentaje'   => $porcentaje,
                'total_pagar'  => $remate->total_pagar,
            ]);
        }

        return redirect()->route('ganadores.index')
                         ->with('success', 'Ganadores registrados con éxito y guardados en la bitácora.');
    }

    /** Mostrar un ganador */
    public function show(string $id)
    {
        $ganador = Ganador::with('remate.race')->findOrFail($id);
        return view('ganadores.show', compact('ganador'));
    }

    /** Formulario para editar un ganador */
    public function edit(string $id)
    {
        $ganador = Ganador::findOrFail($id);
        $remates = Remate::all();

        // Monto sin dividir por empate
        $monto_sin_empate = $ganador->remate->total_pagar ?? 0;

        // Cantidad de ganadores en ese remate
        $total_ganadores = Ganador::where('remate_id', $ganador->remate_id)
            ->where('es_ganador', true)
            ->where('id', '!=', $id) // excluir al actual en edición
            ->count() + ($ganador->es_ganador ? 1 : 0);

        $monto_real = $total_ganadores > 0 ? ($ganador->remate->total_pagar / $total_ganadores) : 0;

        return view('ganadores.edit', compact('ganador', 'remates', 'monto_real', 'monto_sin_empate'));
    }

    /** Actualizar un ganador */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'remate_id' => 'required|exists:remates,id',
            'es_ganador' => 'required|boolean',
            'posicion' => 'nullable|integer',
        ]);

        $ganador = Ganador::findOrFail($id);

        // Solo actualizar remate_id, es_ganador y posicion
        $ganador->update([
            'remate_id'  => $request->remate_id,
            'es_ganador' => $request->es_ganador,
            'posicion'   => $request->posicion,
            // NO tocamos monto_ganado ni porcentaje
        ]);

        return redirect()->route('ganadores.index')
                         ->with('success', 'Ganador actualizado con éxito.');
    }

    /** Eliminar un ganador */
    public function destroy(string $id)
    {
        $ganador = Ganador::findOrFail($id);
        $ganador->delete();

        return redirect()->route('ganadores.index')
                         ->with('success', 'Ganador eliminado con éxito.');
    }

    /** Calcular monto y porcentaje en tiempo real (para AJAX) */
    public function calcularMonto(Request $request)
    {
        $remateIds = $request->remate_ids; // array de IDs seleccionados
        $totalGanadores = count($remateIds);

        $remate = Remate::findOrFail($remateIds[0]); // Tomamos el primer remate para el total

        $monto = $remate->total_pagar / $totalGanadores;
        $porcentaje = 100 / $totalGanadores;

        return response()->json([
            'monto' => number_format($monto, 2, '.', ''),
            'porcentaje' => number_format($porcentaje, 2, '.', '')
        ]);
    }

    /** Generar PDF individual */
    public function generarPDF($id)
    {
        $ganador = Ganador::with('remate.race')->findOrFail($id);

        $pdf = PDF::loadView('ganadores.pdf', compact('ganador'));
        return $pdf->stream('ganador_'.$ganador->id.'.pdf');
    }
}
