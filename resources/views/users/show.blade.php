@extends('layouts.admin')
@section('content')

<h3>Detalles del Usuario</h3>

        <div class="form-group">
            <strong>Nombre:</strong>
            {{ $user->name }}
        </div>

        <div class="form-group">
            <strong>Correo Electronico:</strong>
            {{ $user->email }}
        </div>


        <div class="form-group">
            <strong>Rol:</strong>
            @if(!empty($user->getRoleNames()))
                @foreach($user->getRoleNames() as $v)
                    <span class="badge rounded-pill bg-dark">{{ $v }}</span>
                @endforeach
            @endif
        </div>

        <a href="{{ route('users.index') }}" class="btn btn-secondary">Regresar</a>

@endsection
