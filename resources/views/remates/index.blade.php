@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Lista de Remates</h1>

    <a href="{{ route('remates.create') }}" class="btn btn-primary mb-3">Nuevo Remate</a> <!-- Botón para ir a Create -->

    <form method="GET" class="mb-3">
        <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-secondary">Buscar</button>
    </form>

    <table class="table">
        <thead>
            <tr>
           
                <th>Carrera</th>
                <th>Nro</th>
                <th>Ejemplar</th>
                <th>Cliente</th>
                <th>Monto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($remates as $remate)
            <tr>
              
                <td>{{ $remate->race->name }}</td>
                <td>{{ $remate->number }}</td>
                <td>{{ $remate->ejemplar->name }}</td>
                <td>{{ $remate->cliente }}</td>
                <td>{{ $remate->total }}</td>
                <td>
                    <a href="{{ route('remates.edit', $remate) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('remates.destroy', $remate) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este remate?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $remates->links() }}
</div>
@endsection
