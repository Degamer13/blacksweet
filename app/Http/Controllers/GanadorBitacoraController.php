<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GanadorBitacora;


use PDF;

class GanadorBitacoraController extends Controller
{
    public function index(Request $request)
    {
        $query = GanadorBitacora::with('remate');

        // Filtro por fecha si se envían parámetros
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $bitacoras = $query->orderBy('created_at', 'desc')->get();

        return view('ganadores_bitacora.index', compact('bitacoras'));
    }

    public function generarPDF(Request $request)
    {
        $query = GanadorBitacora::with('remate');

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $bitacoras = $query->orderBy('created_at', 'desc')->get();

        $pdf = PDF::loadView('ganadores_bitacora.pdf', compact('bitacoras'));
           // Descargar el archivo PDF
        return $pdf->download('ganadores_bitacora.pdf');
    }
}
