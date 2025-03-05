@extends('layouts.admin')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-users"></i> Usuarios
                    </h5>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-user-tie"></i> Clientes
                    </h5>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-truck"></i> Proveedores
                    </h5>

                </div>
            </div>
        </div>
    </div>

@endsection
