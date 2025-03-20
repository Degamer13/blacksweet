<?php

namespace App\Http\Controllers;
use App\Models\Remate;
use App\Models\Race;
use App\Models\Bitacora;
use App\Models\EjemplarRace; // Asegúrate de tener el modelo EjemplarRace
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener las fechas de inicio y fin desde la solicitud
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Construir la consulta para filtrar las bitácoras
        $query = Bitacora::query();

        // Filtrar por fecha de inicio si se proporciona
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        // Filtrar por fecha de fin si se proporciona
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Obtener los registros ordenados por race_id, luego number y luego created_at
        $bitacoras = $query->orderBy('race_id', 'asc')
                            ->orderBy('number', 'asc')
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Pasar las bitácoras a la vista
        return view('bitacora.index', compact('bitacoras'));
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
