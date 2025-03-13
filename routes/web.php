<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    AdminHomeController,
    RaceController,
    RemateController,
    EjemplarRaceController,
    GacetaController,
    UserController,
    RoleController,
    PermissionController
};

// Página de bienvenida
Route::get('/', function () {
    return view("welcome");
});

// Rutas de autenticación
Auth::routes();

// Redirección después del login según el rol
Route::get('/redirect', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('home');
    }

    // Si el usuario es admin, lo redirige a adminhome
    if ($user->hasRole('admin')) {
        return redirect()->route('adminhome');
    }

    // Cualquier otro rol lo envía a home
    return redirect()->route('home');
})->middleware('auth');

// Rutas principales
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/panel-admin', [AdminHomeController::class, 'index'])->name('adminhome');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    Route::resources([
        'users' => UserController::class,
        'roles' => RoleController::class,
        'permissions' => PermissionController::class,
        'races' => RaceController::class,
        'remates' => RemateController::class,
        'parametros' => EjemplarRaceController::class,
        'gacetas' => GacetaController::class
    ]);

    Route::get('/ejemplares/{raceId}', [RemateController::class, 'getEjemplarsByRace']);
    Route::get('/validar-ejemplar/{ejemplarId}', [RemateController::class, 'validarEjemplar']);
    Route::post('remates/actualizar', [RemateController::class, 'actualizarRemate'])->name('remates.actualizarRemate');
    Route::patch('/parametros/{id}/toggle-status', [EjemplarRaceController::class, 'toggleStatus'])->name('parametros.toggleStatus');
    Route::get('/registro_remates', [RemateController::class, 'listarRemates'])->name('remates.lista_remates');
});
