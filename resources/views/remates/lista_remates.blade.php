@extends(Auth::user()->hasRole('ventas') ? 'layouts.app1' : 'layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-3 text-center">Registros de Remates</h3>

    <!-- Pestañas dinámicas -->
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
                    <thead>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($remates->where('race_id', $race_id) as $remate)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $remate->ejemplar_name }}</td>
                            <td>{{ $remate->monto1 }}</td>
                            <td>{{$remate->monto2 }}</td>
                            <td>{{ $remate->monto3 }}</td>
                            <td>{{ $remate->monto4 }}</td>
                            <td>{{ $remate->cliente }}</td>
                            <td>{{ $remate->total }}</td>
                            <td>{{ $remate->pote }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        @php
                            $totalMonto1 = $remates->where('race_id', $race_id)->sum('monto1');
                            $totalMonto2 = $remates->where('race_id', $race_id)->sum('monto2');
                            $totalMonto3 = $remates->where('race_id', $race_id)->sum('monto3');
                            $totalMonto4 = $remates->where('race_id', $race_id)->sum('monto4');
                            $totalSubasta = $remates->where('race_id', $race_id)->sum('total');
                            $porcentaje = $totalSubasta * 0.3;
                            $pote = $totalSubasta - $porcentaje;
                        @endphp
                        <tr>
                            <th colspan="2">Totales</th>
                            <th>{{ $totalMonto1 }}</th>
                            <th>{{ $totalMonto2 }}</th>
                            <th>{{ $totalMonto3 }}</th>
                            <th>{{ $totalMonto4 }}</th>
                            <th colspan="3"></th>
                        </tr>
                        <tr>
                            <th colspan="3">Total Subasta</th>
                            <th colspan="3">{{ $totalSubasta }}</th>
                            <th colspan="3"></th>
                        </tr>
                        <tr>
                            <th colspan="3">Porcentaje (-30%)</th>
                            <th colspan="3">{{ $porcentaje }}</th>
                            <th colspan="3"></th>
                        </tr>
                        <tr>
                            <th colspan="3">Pote</th>
                            <th colspan="3">{{ $remates->where('race_id', $race_id)->first()->pote ?? 0 }}</th>
                            <th colspan="3"></th>
                        </tr>
                        <tr>
                            <th colspan="3">Total a pagar</th>
                            <th colspan="3">{{ $pote }}</th>
                            <th colspan="3"></th>
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
