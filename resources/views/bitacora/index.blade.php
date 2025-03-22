@extends('layouts.admin')

@section('content')
<style>
    .nav-tabs .nav-link.active {
        background-color: #6a994e;
        color: white;
        border-color: #6a994e;
        font-size: 20px;
    }

    .table-dark th {
        background-color: #3d405b;
        color: white;
        font-size: 20px;
    }

    table tbody input.form-control {
        font-weight: bold !important;
    }

    tfoot tr th {
        font-weight: bold;
        background-color: #ddb892;
        font-size: 20px;
    }

    .alert {
        margin-top: 20px;
        font-size: 20px;
    }

    table tbody tr:nth-child(odd) {
        background-color: #f4f1de;
    }

    table tbody tr:nth-child(even) {
        background-color: #e9edc9;
    }

    table tbody td:nth-child(1) {
        background-color: #bc6c25;
        color: white;
        font-weight: bold;
    }

    table tbody td:nth-child(2) {
        background-color: #3d405c;
        color: white;
        font-weight: bold;
    }

    table tbody td:nth-child(3),
    table tbody td:nth-child(4),
    table tbody td:nth-child(5),
    table tbody td:nth-child(6) {
        background-color: #ffcb69;
        color: black;
        font-weight: bold;
    }

    table tbody td:nth-child(7) {
        background-color: #6a994e;
        color: black;
        font-weight: bold;
    }

    table tbody td:nth-child(8) {
        background-color: #8ecae6;
        color: black;
        font-weight: bold;
    }

    table tbody td:nth-child(9) {
        background-color: #ddb892;
        color: black;
        font-weight: bold;
    }

    table tbody td:nth-child(10) {
        background-color: #faf9f8;
        color: black;
        font-weight: bold !important;
    }

    /* Evitar desbordamiento en la barra lateral y el contenido */
    .main-sidebar {
        position: fixed;
        z-index: 1050;
        height: 100vh; /* Fijar la altura de la barra lateral al 100% de la pantalla */
    }

    .content-wrapper {
        margin-left: 250px; /* Ajusta el margen según el tamaño de la barra lateral */
        min-height: 100vh;  /* Asegura que el contenido ocupe al menos el alto de la pantalla */
        overflow: auto; /* Permite el desplazamiento si el contenido es demasiado largo */
    }

    #carreras_container {
        max-height: 70vh; /* Limita la altura del contenedor de las carreras */
        overflow-y: auto; /* Activa el desplazamiento vertical si es necesario */
    }

    /* Para el modal de ejemplares, asegúrate de que el contenido no se desborde */
    .modal-dialog {
        max-width: 90%; /* Limita el ancho del modal */
        margin: 1.75rem auto; /* Centra el modal */
    }

</style>

<div class="container">
    <h3 class="mb-3 text-center">Registros de Bitácoras</h3>
@can("bitacora-search")


    <!-- Formulario de filtro por fecha -->
    <form method="GET" action="{{ route('bitacora.index') }}" class="row mb-4">
        <div class="col-md-4">
            <label for="start_date" class="form-label">Fecha de Inicio:</label>
            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-4">
            <label for="end_date" class="form-label">Fecha de Fin:</label>
            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100">Filtrar</button>
        </div>
    </form>
    @endcan
 @can("bitacora-pdf")


    <!-- Botón para generar el PDF -->
    <form method="GET" action="{{ route('bitacora.generarPDF') }}" class="mb-4">
        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
        <button type="submit" class="btn btn-danger w-100">Generar PDF</button>
    </form>
    @endcan
    @if ($bitacoras->isEmpty())
        <div class="alert alert-warning text-center">
            No hay registros de bitácora disponibles en el rango seleccionado.
        </div>
    @else
        <!-- Tabs por carreras -->
        <ul class="nav nav-tabs" id="bitacorasTabs" role="tablist">
            @foreach ($bitacoras as $race_id => $grupoBitacoras)
                <li class="nav-item">
                    <a class="nav-link @if($loop->first) active @endif"
                       id="race{{ $race_id }}-tab"
                       data-bs-toggle="tab"
                       href="#race{{ $race_id }}"
                       role="tab">
                        Carrera {{ $race_id }}
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Contenido de las carreras -->
        <div class="tab-content mt-3" id="bitacorasTabsContent">
            @foreach ($bitacoras as $race_id => $grupoBitacoras)
                <div class="tab-pane fade @if($loop->first) show active @endif"
                     id="race{{ $race_id }}" role="tabpanel">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nro</th>
                                    <th>Ejemplar</th>
                                    <th>Cliente</th>
                                    <th>Monto1</th>
                                    <th>Monto2</th>
                                    <th>Monto3</th>
                                    <th>Monto4</th>
                                    <th>Total</th>
                                    <th>Pote</th>
                                    <th>Acumulado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($grupoBitacoras as $bitacora)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bitacora->ejemplar_name ?? 'N/A' }}</td>
                                    <td>{{ $bitacora->cliente ?? 'N/A' }}</td>
                                    <td>{{ $bitacora->monto1 ?? 0 }}</td>
                                    <td>{{ $bitacora->monto2 ?? 0 }}</td>
                                    <td>{{ $bitacora->monto3 ?? 0 }}</td>
                                    <td>{{ $bitacora->monto4 ?? 0 }}</td>
                                    <td>{{ $bitacora->total ?? 0 }}</td>
                                    <td>{{ $bitacora->pote ?? 0 }}</td>
                                    <td>{{ $bitacora->acumulado ?? 0 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                @php
                                    $totalMonto1 = $grupoBitacoras->sum('monto1');
                                    $totalMonto2 = $grupoBitacoras->sum('monto2');
                                    $totalMonto3 = $grupoBitacoras->sum('monto3');
                                    $totalMonto4 = $grupoBitacoras->sum('monto4');
                                    $totalSubasta = $grupoBitacoras->sum('total');
                                    $porcentaje = $totalSubasta * 0.3;
                                    $pote = $grupoBitacoras->first()->pote ?? 0;
                                    $acumulado = $grupoBitacoras->first()->acumulado ?? 0;
                                    $totalPagar = $grupoBitacoras->first()->total_pagar ?? 0;
                                @endphp
                                <tr>
                                    <th colspan="3">Totales</th>
                                    <th>{{ $totalMonto1 }}</th>
                                    <th>{{ $totalMonto2 }}</th>
                                    <th>{{ $totalMonto3 }}</th>
                                    <th>{{ $totalMonto4 }}</th>
                                    <th colspan="3"></th>
                                </tr>
                                <tr>
                                    <th colspan="3">Total Subasta</th>
                                    <th colspan="3">{{ $totalSubasta }}</th>
                                    <th colspan="4"></th>
                                </tr>
                                <tr>
                                    <th colspan="3">Porcentaje (-30%)</th>
                                    <th colspan="3">{{ $porcentaje }}</th>
                                    <th colspan="4"></th>
                                </tr>
                                <tr>
                                    <th colspan="3">Pote</th>
                                    <th colspan="3">{{ $pote }}</th>
                                    <th colspan="4"></th>
                                </tr>
                                <tr>
                                    <th colspan="3">Acumulado</th>
                                    <th colspan="3">{{ $acumulado }}</th>
                                    <th colspan="4"></th>
                                </tr>
                                <tr>
                                    <th colspan="3">Total a pagar</th>
                                    <th colspan="3">{{ $totalPagar }}</th>
                                    <th colspan="4"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
