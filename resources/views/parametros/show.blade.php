@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Ejemplares de la Carrera: {{ $race->name }}</h2>
    <a href="{{ route('parametros.index') }}" class="btn btn-secondary mb-3">Volver</a>

    @if ($race->ejemplarRaces->isEmpty())
        <div class="alert alert-warning">
            No hay ejemplares registrados para esta carrera.
        </div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Ejemplar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($race->ejemplarRaces as $ejemplarRace)
                    <tr>
                        <td>{{ $ejemplarRace->id }}</td>
                        <td>{{ $ejemplarRace->ejemplar_name }}</td>
                        <td>{{ $ejemplarRace->status == 'activar' ? 'Activo' : 'Desactivado' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
