<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    AdminHomeController,
    RaceController,
    RemateController,
    EjemplarRaceController,
    UserController,
    RoleController,
    PermissionController,
    BitacoraController
};

// Página de bienvenida
Route::get('/', function () {
    return view("welcome");
});

// Rutas de autenticación
Auth::routes();

// Rutas protegidas y basadas en roles
Route::middleware(['auth'])->group(function () {

    // Rutas comunes para remates y parámetros (accesibles para ambos roles)
    Route::get('/ejemplares/{raceId}', [RemateController::class, 'getEjemplarsByRace']);
    Route::patch('/parametros/{id}/toggle-status', [EjemplarRaceController::class, 'toggleStatus'])->name('parametros.toggleStatus');
    Route::get('/registro_remates', [RemateController::class, 'listarRemates'])->name('remates.lista_remates');
    Route::delete('/remates/destroyAll', [RemateController::class, 'destroyAll'])->name('remates.destroyAll');
    Route::delete('/ejemplar_race/destroyAll', [EjemplarRaceController::class, 'destroyAll'])->name('ejemplar_race.destroyAll');
    Route::get('/registro_logros_remates', [RemateController::class, 'LogrosRemates'])->name('remates.logros_remates');

    // Rutas específicas para administradores
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/panel-admin', [AdminHomeController::class, 'index'])->name('adminhome');
        Route::resources([
            'users' => UserController::class,
            'roles' => RoleController::class,
            'permissions' => PermissionController::class,
            'bitacora' => BitacoraController::class,
        ]);
    });

    // Rutas para "races", "remates" y "parametros" (accesibles por admin y ventas)
    Route::middleware(['role:admin|ventas'])->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::resources([
            'races' => RaceController::class,
            'remates' => RemateController::class,
            'parametros' => EjemplarRaceController::class,
        ]);
    });

});
