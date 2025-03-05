@extends('layouts.admin')
@section('content')

<h3>Registrar Usuario</h3>
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

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
                            <input type="text" name="name" class="form-control" placeholder="Nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo Electrónico:</label>
                            <input type="email" name="email" class="form-control" placeholder="Correo Electrónico" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Contraseña:</label>
                            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Confirmar Contraseña:</label>
                            <input type="password" name="confirm-password" class="form-control" placeholder="Confirmar Contraseña" required>
                        </div>
                      <div class="mb-3">
    <label for="roles" class="font-weight-bold">Rol:</label>
    <select name="roles[]" id="roles" class="form-control" multiple required>
        @foreach ($roles as $role)
            <option value="{{ $role }}">{{ $role }}</option>
        @endforeach
    </select>
</div>

                         <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
          
@endsection
