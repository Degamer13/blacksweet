@extends('layouts.admin')

@section('content')

<h3>Editar Rol</h3>

                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre:</label>
                            <input type="text" name="name" class="form-control" value="{{ $role->name }}" placeholder="Nombre" required>
                        </div>

                        <div class="mb-3">
                            <label class="font-weight-bold">Permisos:</label>
                            <select name="permissions[]" class="form-control" multiple required>
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->id }}"
                                        {{ in_array($permission->id, $rolePermissions) ? 'selected' : '' }}>
                                        {{ $permission->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                       <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>                    </form>
              

@endsection
