@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-lg-9">
            <h1>Editar Ganador</h1>
        </div>
        <div class="col-lg-3 text-right">
            <a class="btn btn-secondary" href="{{ route('ganadores.index') }}">
                <!-- Icono de volver SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                     viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H2.707l4.147 4.146a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 0 1 .708.708L2.707 7.5H14.5A.5.5 0 0 1 15 8z"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Oops!</strong> Hubo algunos problemas con los datos:<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ganadores.update', $ganador->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Selección del remate --}}
        <div class="mb-3">
            <label for="remate_id" class="form-label">Remate (Carrera - Ejemplar - Cliente)</label>
            <select name="remate_id" id="remate_id" class="form-control" required>
                <option value="">-- Selecciona un remate --</option>
                @foreach($remates as $remate)
                    <option value="{{ $remate->id }}"
                        {{ $remate->id == $ganador->remate_id ? 'selected' : '' }}>
                        Carrera {{ $remate->race->name ?? $remate->race_id }} - {{ $remate->ejemplar_name }} ({{ $remate->cliente }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Es ganador --}}
        <div class="mb-3">
            <label class="form-label">¿Es ganador?</label>
            <select name="es_ganador" id="es_ganador" class="form-control" required>
                <option value="1" {{ $ganador->es_ganador ? 'selected' : '' }}>✅ Sí</option>
                <option value="0" {{ !$ganador->es_ganador ? 'selected' : '' }}>❌ No</option>
            </select>
        </div>

        {{-- Posición --}}
        <div class="mb-3">
            <label for="posicion" class="form-label">Posición (opcional)</label>
            <input type="number" name="posicion" id="posicion" class="form-control"
                   value="{{ old('posicion', $ganador->posicion) }}" min="1" placeholder="Ej: 1">
        </div>

        {{-- Montos (solo referencia, no se pueden editar) --}}
        <div class="mb-3">
            <label class="form-label">Monto ganado</label>
            <input type="text" class="form-control" value="{{ number_format($ganador->monto_ganado, 2) }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Porcentaje</label>
            <input type="text" class="form-control" value="{{ number_format($ganador->porcentaje, 2) }} %" disabled>
        </div>


            <button type="submit" class="btn btn-primary">
                 Actualizar
            </button>

    </form>
</div>
@endsection
