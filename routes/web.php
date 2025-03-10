<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\{
    HomeController,
    AdminHomeController,
    RaceController,
    EjemplarController,
    RemateController,
    EjemplarRaceController
};
use App\Models\Ejemplar; // Asegúrate de importar el modelo Ejemplar

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
| Aquí es donde puedes registrar las rutas web para tu aplicación.
| Las rutas se cargan mediante el RouteServiceProvider y todas ellas serán
| asignadas al grupo de middleware "web".
| 
*/

// Login
Route::get('/', function () {
    return view("welcome");
});

// Rutas de autenticación
Auth::routes();

// Ruta del panel principal
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Ruta para la vista adminhome con el controlador
Route::get('/panel-admin', [AdminHomeController::class, 'index'])->name('adminhome');

// Rutas protegidas para el sistema de inventario
Route::middleware(['auth'])->group(function () {
    Route::resources([
        'users' => UserController::class,
        'roles' => RoleController::class,
        'permissions' => PermissionController::class,
        'races' => RaceController::class,
        'ejemplars' => EjemplarController::class,
        'remates' => RemateController::class,
        'parametros' => EjemplarRaceController::class
    ]);

  // Ruta para obtener los ejemplares de una carrera
    Route::get('/ejemplares/{race_id}', [RemateController::class, 'getEjemplarsByRace'])->name('ejemplares.byRace');
});
