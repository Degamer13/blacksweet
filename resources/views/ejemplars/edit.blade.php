@extends('layouts.admin')

@section('content')

<h3>Editar Ejemplar</h3>
<form action="{{ route('ejemplars.update', $ejemplar->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="name" value="{{ $ejemplar->name }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="{{ route('ejemplars.index') }}" class="btn btn-secondary">Cancelar</a>
</form>

@endsection
