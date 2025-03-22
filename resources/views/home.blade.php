@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header text-white text-center" style="background: url('https://source.unsplash.com/800x200/?horse,race') no-repeat center; background-size: cover;">
                    <h2 class="p-3 font-weight-bold" style="background: rgba(0, 0, 0, 0.6); border-radius: 8px;">🏇 Panel de Ventas de Remates</h2>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success text-center font-weight-bold" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="text-center">
                        <h4 class="text-secondary font-weight-bold">¡Bienvenido al módulo de ventas de remates de carreras de caballos!</h4>
                        <p class="text-muted">Aquí puedes gestionar y visualizar todas las transacciones de ventas de los ejemplares y sus participaciones en las carreras.</p>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
