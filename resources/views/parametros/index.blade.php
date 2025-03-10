@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Listado de Carreras con Ejemplares</h2>
    @can('parametro-create')
    <a href="{{ route('parametros.create') }}" class="btn btn-primary mb-3">Agregar Ejemplares a Carrera</a>
    @endcan
    <!-- Formulario de búsqueda -->
     <div class="col-12 col-md-6 mb-3">
         <form method="GET" action="{{ route('parametros.index') }}">
            <div class="input-group">
                 <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar carrera">
                <div class="input-group-append">
                    <button class="btn btn-success" id="button-addon2" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
@if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table id="ejemplares_table" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Carrera</th>
                <th>Estado</th> <!-- Cambié "Ejemplares Activos" por "Estado" -->
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($races as $race)
                <tr>
                    <td>{{ $race->id }}</td>
                    <td>{{ $race->name }}</td>
                    <td>
                        <!-- Comprobar si hay ejemplares activos y mostrar el mensaje correspondiente -->
                        @php
                            $activeEjemplars = $race->ejemplarRaces()->where('status', 'activar')->get();
                        @endphp
                        @if($activeEjemplars->isNotEmpty())
                            <p>Carrera Activada</p> <!-- Mensaje cuando hay ejemplares activos -->
                        @else
                            <p>Carrera Desactivada</p>
                        @endif
                    </td>
                    <td>
                        @can('parametro-show')
                        <!-- Acciones de editar y eliminar -->
                        <a href="{{ route('parametros.show', $race->id) }}" class="btn btn-info"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                </svg></a>
                          @endcan      
                       
                          @can('parametro-edit')
                        <a href="{{ route('parametros.edit', $race->id) }}" class="btn btn-primary"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                                        <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
                                    </svg></a>
                                    @endcan

                     @can('parametro-delete')
                          <!-- Eliminar solo si existen relaciones activas -->
                        @if($activeEjemplars->isEmpty())
                            <form action="{{ route('parametros.destroy', $race->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                        </svg></button>
                            </form>
                              @endcan
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    {{ $races->links() }}
</div>
</div>
@endsection
