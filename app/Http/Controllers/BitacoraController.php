<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BitacoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtén las fechas desde el formulario, si están presentes
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
    
        // Filtrado por fechas si existen
        $bitacorasQuery = Bitacora::query(); // Asumiendo que Bitacora es el nombre del modelo
    
        if ($start_date) {
            $bitacorasQuery->whereDate('created_at', '>=', $start_date);
        }
    
        if ($end_date) {
            $bitacorasQuery->whereDate('created_at', '<=', $end_date);
        }
    
        // Obtenemos las bitácoras filtradas
        $bitacoras = $bitacorasQuery->get()->groupBy('race_id'); // Agrupar por carrera (race_id)
    
        return view('bitacora.index', compact('bitacoras'));
    }

    /**
     * Genera el PDF de las bitácoras, filtrando por fecha si es necesario.
     */
    public function generarPDF(Request $request)
    {
        // Obtén las fechas desde el formulario
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
    
        // Filtrado por fechas si existen
        $bitacorasQuery = Bitacora::query(); // Asumiendo que Bitacora es el nombre del modelo
    
        if ($start_date) {
            $bitacorasQuery->whereDate('created_at', '>=', $start_date);
        }
    
        if ($end_date) {
            $bitacorasQuery->whereDate('created_at', '<=', $end_date);
        }
    
        // Obtenemos las bitácoras filtradas
        $bitacoras = $bitacorasQuery->get()->groupBy('race_id'); // Agrupar por carrera (race_id)
    
        // Generar PDF con los datos
        $pdf = Pdf::loadView('bitacora.pdf', compact('bitacoras'));
    
        // Descargar el archivo PDF
        return $pdf->download('bitacora_registros.pdf');
    }

    // Otros métodos de tu controlador, como create, store, etc.
}
