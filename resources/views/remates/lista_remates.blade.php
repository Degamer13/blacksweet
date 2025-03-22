@extends(Auth::user()->hasRole('ventas') ? 'layouts.app1' : 'layouts.admin')
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

    tfoot tr th {
        font-weight: bold;
        background-color: #ddb892;
        font-size: 20px;
    }

    .alert {
        margin-top: 20px;
        font-size: 20px;
    }

    table tbody tr:nth-child(odd) { background-color: #f4f1de; }
    table tbody tr:nth-child(even) { background-color: #e9edc9; }

    table tbody td:nth-child(1) { background-color: #bc6c25; color: white; font-weight: bold; }
    table tbody td:nth-child(2) { background-color: #3d405b; color: white; font-weight: bold !important; }
    table tbody td:nth-child(3),
    table tbody td:nth-child(4),
    table tbody td:nth-child(5),
    table tbody td:nth-child(6) { background-color: #ffcb69; color: black; font-weight: bold !important; }
    table tbody td:nth-child(7) { background-color: #6a994e; color: black; font-weight: bold !important; }
    table tbody td:nth-child(8) { background-color: #8ecae6; color: black; font-weight: bold !important; }
    table tbody td:nth-child(9) { background-color: #ddb892; color: black; font-weight: bold !important; }
    table tbody td:nth-child(10) { background-color:#faf9f8; color: black; font-weight: bold !important; }

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
 @can('remate-edit')
     

    <a href="{{ route('remates.edit.global') }}" class="btn btn-primary">
        <i class="fas fa-edit"></i> Editar Todos los Remates
    </a>
    @endcan

    <h3 class="mb-3 text-center">Registros de Remates</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($ejemplares->isEmpty())
        <div class="alert alert-warning text-center">
            No hay carreras registradas.
        </div>
    @else
        <ul class="nav nav-tabs" id="rematesTabs" role="tablist">
            @foreach ($ejemplares as $race_id => $grupoEjemplares)
                <li class="nav-item">
                    <a class="nav-link @if($loop->first) active @endif" id="race{{ $race_id }}-tab" data-bs-toggle="tab" href="#race{{ $race_id }}" role="tab">
                        Carrera {{ $race_id }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content mt-3" id="rematesTabsContent">
            @foreach ($ejemplares as $race_id => $grupoEjemplares)

            <div class="tab-pane fade @if($loop->first) show active @endif" id="race{{ $race_id }}" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nro</th>
                                <th>Ejemplar</th>
                                <th>Monto1</th>
                                <th>Monto2</th>
                                <th>Monto3</th>
                                <th>Monto4</th>
                                <th>Cliente</th>
                                <th>Monto</th>
                                <th>Pote</th>
                                <th>Acumulado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($remates[$race_id] ?? [] as $remate)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $remate->ejemplar_name }}</td>
                                <td>{{ $remate->monto1 }}</td>
                                <td>{{ $remate->monto2 }}</td>
                                <td>{{ $remate->monto3 }}</td>
                                <td>{{ $remate->monto4 }}</td>
                                <td>{{ $remate->cliente }}</td>
                                <td>{{ $remate->total }}</td>
                                <td>{{ $remate->pote }}</td>
                                <td>{{ $remate->acumulado }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            @php
                                $grupoRemates = $remates[$race_id] ?? collect([]);
                                $totalMonto1 = $grupoRemates->sum('monto1');
                                $totalMonto2 = $grupoRemates->sum('monto2');
                                $totalMonto3 = $grupoRemates->sum('monto3');
                                $totalMonto4 = $grupoRemates->sum('monto4');
                                $totalSubasta = $grupoRemates->sum('total');
                                $porcentaje = $totalSubasta * 0.3;
                                $pote = $grupoRemates->first()->pote ?? 0;
                                $acumulado = $grupoRemates->first()->acumulado ?? 0;
                                $totalPagar = $grupoRemates->first()->total_pagar ?? 0;
                            @endphp
                            <tr>
                                <th colspan="2">Totales</th>
                                <th>{{ $totalMonto1 }}</th>
                                <th>{{ $totalMonto2 }}</th>
                                <th>{{ $totalMonto3 }}</th>
                                <th>{{ $totalMonto4 }}</th>
                                <th colspan="4"></th>
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
