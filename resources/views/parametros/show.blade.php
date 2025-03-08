@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Ejemplares de la Carrera: {{ $race->name }}</h2>
    <a href="{{ route('parametros.index') }}" class="btn btn-secondary mb-3">Volver</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Ejemplar</th>
                <th>Status</th>
             <!--   <th>Acciones</th>-->
            </tr>
        </thead>
        <tbody>
            @foreach($ejemplars as $ejemplarRace)
                <tr>
                    <td>{{ $ejemplarRace->id }}</td>
                    <td>{{ $ejemplarRace->ejemplar->name }}</td>
                    <td>{{ $ejemplarRace->status }}</td>
                   <!-- <td>
                        <a href="{{ route('parametros.edit', $ejemplarRace->id) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('parametros.destroy', $ejemplarRace->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>-->
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
