
@extends('layouts.admin')

@section('content')

    <h3>Editar Carrera</h3>
    <form action="{{ route('races.update', $race->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="name" value="{{ $race->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('races.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

@endsection
