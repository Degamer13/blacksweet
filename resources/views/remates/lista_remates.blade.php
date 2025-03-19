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

    table tbody tr:nth-child(odd) {
        background-color: #f4f1de;
    }

    table tbody tr:nth-child(even) {
        background-color: #e9edc9;
    }

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
</style>

<div class="container">
    <h3 class="mb-3 text-center">Registros de Remates</h3>

    <ul class="nav nav-tabs" id="rematesTabs" role="tablist">
        @foreach ($ejemplares as $race_id => $grupoEjemplares)
            <li class="nav-item">
                <a class="nav-link @if($loop->first) active @endif" id="race{{ $race_id }}-tab" data-bs-toggle="tab" href="#race{{ $race_id }}" role="tab">Carrera {{ $race_id }}</a>
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
                            $totalMonto1 = $remates[$race_id]->sum('monto1') ?? 0;
                            $totalMonto2 = $remates[$race_id]->sum('monto2') ?? 0;
                            $totalMonto3 = $remates[$race_id]->sum('monto3') ?? 0;
                            $totalMonto4 = $remates[$race_id]->sum('monto4') ?? 0;
                            $totalSubasta = $remates[$race_id]->sum('total') ?? 0;
                            $porcentaje = $totalSubasta * 0.3;
                            $pote = $remates[$race_id]->first()->pote ?? 0;
                            $acumulado = $remates[$race_id]->first()->acumulado ?? 0;
                            $totalPagar = $remates[$race_id]->first()->total_pagar ?? 0;
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
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
