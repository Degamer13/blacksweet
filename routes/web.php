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
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
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
        'users'=> UserController::class,
        'roles'=> RoleController::class,
        'permissions'=>PermissionController::class,
        'races'=>RaceController::class,
        'ejemplars'=>EjemplarController::class,
        'remates'=>RemateController::class,
        'parametros'=>EjemplarRaceController::class
    ]);


});
