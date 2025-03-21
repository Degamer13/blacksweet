@extends('layouts.admin')

@section('content')
<style>


    /* Evitar desbordamiento en la barra lateral y el contenido */
    .main-sidebar {
        position: fixed;
        z-index: 1050;
        height: 100vh; /* Fijar la altura de la barra lateral al 100% de la pantalla */
    }
    
    .content-wrapper {
        margin-left: 250px; /* Ajusta el margen según el tamaño de la barra lateral */
        min-height: 100vh;  /* Asegura que el contenido ocupe al menos el alto de la pantalla */
        overflow: auto; /* Permite el desplazamiento si el contenido es demasiado largo */
    }
    
    #carreras_container {
        max-height: 70vh; /* Limita la altura del contenedor de las carreras */
        overflow-y: auto; /* Activa el desplazamiento vertical si es necesario */
    }
    
    /* Para el modal de ejemplares, asegúrate de que el contenido no se desborde */
    .modal-dialog {
        max-width: 90%; /* Limita el ancho del modal */
        margin: 1.75rem auto; /* Centra el modal */
    }
    
     </style>
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
