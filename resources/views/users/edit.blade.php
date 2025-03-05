@extends('layouts.admin')
@section('content')

<h3>Editar Usuario</h3>

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif


                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre:</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" placeholder="Nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo Electrónico:</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" placeholder="Correo Electrónico" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Contraseña (opcional):</label>
                            <input type="password" name="password" class="form-control" placeholder="Nueva Contraseña">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Confirmar Contraseña:</label>
                            <input type="password" name="confirm-password" class="form-control" placeholder="Confirmar Nueva Contraseña">
                        </div>
                        <div class="mb-3">
    <label for="roles" class="font-weight-bold">Rol:</label>
    <select name="roles[]" id="roles" class="form-control" multiple required>
        @foreach ($roles as $role)
            <option value="{{ $role }}" {{ in_array($role, $userRole) ? 'selected' : '' }}>{{ $role }}</option>
        @endforeach
    </select>
</div>
  <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                
@endsection
