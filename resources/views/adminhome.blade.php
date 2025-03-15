@extends('layouts.admin')

@section('content')

<div class="container text-center mt-4">
    <h2 class="text-primary">🏇 Bienvenido, Administrador! 🏆</h2>
    <p class="lead">Prepárate para una jornada emocionante de apuestas y remates de caballos.</p>
</div>

<div class="row justify-content-center text-center mt-4">
    <div class="col-md-5">
        <div class="card shadow-lg">
            <div class="card-body">
                <i class="fas fa-users fa-3x text-primary"></i>
                <h4 class="mt-3">Usuarios</h4>
                <p class="font-weight-bold">Total: {{ $cantidadUsuarios }}</p>

            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card shadow-lg">
            <div class="card-body">
                <i class="fas fa-user-shield fa-3x text-warning"></i>
                <h4 class="mt-3">Roles</h4>
                <p class="font-weight-bold">Total: {{ $cantidadRoles }}</p>

            </div>
        </div>
    </div>
</div>

@endsection
