@extends('layouts.admin')
@section('content')

<h3>Detalles del Rol</h3>

        <div class="form-group">
            <strong>Nombre:</strong>
            {{ $role->name }}
        </div>


        <div class="form-group">
            <strong>Permisos:</strong>
            @if(!empty($rolePermissions))
                @foreach($rolePermissions as $v)
                    <label class="label label-success">{{ $v->name }},</label>
                @endforeach
            @endif
        </div>


        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Regresar</a>
@endsection
