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
            <label for="ejemplars">Ejemplares</label>
            <select name="ejemplars[]" class="form-control" multiple required>
                @foreach($ejemplars as $ejemplar)
                    <option value="{{ $ejemplar->id }}" 
                        @if(in_array($ejemplar->id, $selectedEjemplars)) selected @endif>
                        {{ $ejemplar->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="status">Estado</label>
            <select name="status" class="form-control" required>
                <option value="activado" {{ $status == 'activado' ? 'selected' : '' }}>Activado</option>
                <option value="desactivado" {{ $status == 'desactivado' ? 'selected' : '' }}>Desactivado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
    </form>

    <a href="{{ route('parametros.index') }}" class="btn btn-secondary mt-3">Volver al listado</a>
</div>
@endsection
