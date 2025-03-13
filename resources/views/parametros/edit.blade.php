@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Editar Relación Carrera-Ejemplar</h2>

    <form action="{{ route('parametros.update', $race->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="race_id">Carrera</label>
            <select name="race_id" class="form-control" required>
                <option value="{{ $race->id }}" selected>{{ $race->name }}</option>
                @foreach($races as $raceOption)
                    <option value="{{ $raceOption->id }}">{{ $raceOption->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="ejemplar_name">Ejemplares</label>
            @foreach($ejemplars as $ejemplar)
                <div class="d-flex mb-2">
                    <!-- Campo para editar el nombre del ejemplar -->
                    <input type="text" name="ejemplar_name[{{ $ejemplar->id }}]" class="form-control"
                           value="{{ old('ejemplar_name.' . $ejemplar->id, $ejemplar->ejemplar_name) }}" required>
                    <input type="hidden" name="ejemplar_id[]" value="{{ $ejemplar->id }}">
                </div>
            @endforeach
        </div>
        <input type="hidden" name="status" value="{{ old('status', $status) }}">


        <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
    </form>

    <a href="{{ route('parametros.index') }}" class="btn btn-secondary mt-3">Volver al listado</a>
</div>
@endsection
