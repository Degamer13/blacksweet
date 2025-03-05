
@extends('layouts.admin')
@section('content')

<h3>Registrar Permiso</h3>
                    <form action="{{ route('permissions.store') }}" method="POST">
                        @csrf

                        @if (count($errors) > 0)
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
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
               
@endsection
