<?php

namespace App\Http\Controllers;

use App\Models\Ejemplar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EjemplarController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ejemplar-list|ejemplar-create|ejemplar-edit|ejemplar-delete', ['only' => ['index','store']]);
        $this->middleware('permission:ejemplar-create', ['only' => ['create','store']]);
        $this->middleware('permission:ejemplar-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:ejemplar-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $buscarpor = $request->get('buscarpor');
        $ejemplars = Ejemplar::where('name', 'like', '%' . $buscarpor . '%')->paginate(5);
        return view('ejemplars.index', compact('ejemplars', 'buscarpor'));
    }

    public function create()
    {
        return view('ejemplars.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Ejemplar::create([
            'name' => $request->name,
        ]);

        return redirect()->route('ejemplars.index')->with("success", "Ejemplar registrado con éxito");
    }
    public function validarEjemplar($name)
    {
        $name = trim($name); // Elimina espacios en blanco innecesarios

        if (empty($name)) {
            return response()->json(['message' => '⚠️ El nombre no puede estar vacío'], 400);
        }

        $existe = Ejemplar::where('name', Str::lower($name))->exists(); // Normaliza a minúsculas

        if ($existe) {
            return response()->json(['message' => '❌ Ya se encuentra registrado el ejemplar'], 400);
        } else {
            return response()->json(['message' => '✅ Felicidades, el ejemplar está disponible'], 200);
        }
    }

    public function show(Ejemplar $ejemplar)
    {
        return view('ejemplars.show', compact('ejemplar'));
    }

    public function edit(Ejemplar $ejemplar)
{
    return view('ejemplars.edit', compact('ejemplar'));
}

    public function update(Request $request, Ejemplar $ejemplar)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $ejemplar->update([
            'name' => $request->name,
        ]);

        return redirect()->route('ejemplars.index')->with("success", "Ejemplar actualizado con éxito");
    }

    public function destroy(Ejemplar $ejemplar)
    {
        $ejemplar->delete();
        return redirect()->route('ejemplars.index')->with("success", "Ejemplar eliminado con éxito");
    }
}
