@extends('layouts.admin')

@section('content')
<style>
    .nav-tabs .nav-link.active {
        background-color: #6a994e;
        color: white;
        border-color: #6a994e;
        font-size: 18px;
    }
    .table-dark th {
        background-color: #3d405b;
        color: white;
    }
    table tbody tr:nth-child(odd) {
        background-color: #f4f1de;
    }
    table tbody tr:nth-child(even) {
        background-color: #e9edc9;
    }
</style>

<div class="container">
    <h3 class="mb-3 text-center">Bitácora de Ganadores</h3>

    <!-- Filtros -->
    <form method="GET" action="{{ route('ganadores_bitacora.index') }}" class="row mb-4">
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

    <!-- PDF -->
    <form method="GET" action="{{ route('ganadores_bitacora.generarPDF') }}" class="mb-4">
        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
        <button type="submit" class="btn btn-danger w-100">Generar PDF</button>
    </form>

    @if ($bitacoras->isEmpty())
        <div class="alert alert-warning text-center">
            No hay registros en el rango seleccionado.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>

                        <th>Carrera</th>
                        <th>Ejemplar</th>
                        <th>Cliente</th>
                        <th>Ganador</th>
                        <th>Posición</th>
                        <th>Monto Ganado</th>
                        <th>Porcentaje</th>
                        <th>Total del Remate</th>
                        <th>Fecha Registro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bitacoras as $bitacora)
                        <tr>

                            <td>{{ $bitacora->remate->race_id }}</td>
                            <td>{{ $bitacora->remate->ejemplar_name }}</td>
                            <td>{{ $bitacora->remate->cliente }}</td>

                            <td>{{ $bitacora->es_ganador ? 'Sí' : 'No' }}</td>
                            <td>{{ $bitacora->posicion ?? '-' }}</td>
                            <td>{{ number_format($bitacora->monto_ganado, 2) }}</td>
                            <td>{{ number_format($bitacora->porcentaje, 2) }}%</td>
                            <td>{{ number_format($bitacora->total_pagar, 2) }}</td>
                            <td>{{ $bitacora->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
