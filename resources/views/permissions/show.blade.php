@extends('layouts.admin')
@section('content')


        <h3>Detalle del Permiso</h3>

    
        <div class="form-group">
            <strong>Nombre:</strong> {{ $permission->name }}
        </div>


        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Regresar</a>

@endsection
