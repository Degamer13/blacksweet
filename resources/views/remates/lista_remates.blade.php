@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Registros de Remates</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Carrera</th>
                <th>Ejemplar</th>
                <th>Cliente</th>
                <th>Monto Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($remates as $remate)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $remate->race_id }}</td>
                <td>{{ $remate->ejemplar_name }}</td>
                <td>{{ $remate->cliente }}</td>
                <td>{{ number_format($remate->total, 2) }}</td>
                <td>
                    <a href="{{ route('remates.show', $remate->id) }}" class="btn btn-info btn-sm">Ver</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    {{ $remates->links() }}
</div>
@endsection
