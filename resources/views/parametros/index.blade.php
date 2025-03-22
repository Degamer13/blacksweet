@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Listado de Carreras con Ejemplares</h2>
    @can('parametro-create')
    <a href="{{ route('parametros.create') }}" class="btn btn-primary mb-3">Agregar Ejemplares a Carrera</a>
    @endcan
    
    @can('parametro-delete')
    <form id="reiniciar-form" action="{{ route('ejemplar_race.destroyAll') }}" method="POST" >
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-secondary" id="reiniciar-btn">
            <i class="fas fa-redo"></i> Reiniciar los Registros de los Ejemplares de las Carreras
        </button>
    </form>
    @endcan
<br>
@can('parametro-search')
    

    <div class="col-12 col-md-6 mb-3">
        <form method="GET" action="{{ route('parametros.index') }}">
            <div class="input-group">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar carrera">
                <div class="input-group-append">
                    <button class="btn btn-success" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endcan

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table id="ejemplares_table" class="table table-bordered">
        <thead>
            <tr>
               
                <th>Carrera</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($races as $race)
                <tr>
                  
                    <td>{{ $race->name }}</td>
                    <td>
                        @php
                            $activeEjemplars = $race->ejemplarRaces()->where('status', 'activar')->exists();
                        @endphp
                        <p>{{ $activeEjemplars ? 'Carrera Activada' : 'Carrera Desactivada' }}</p>
                    </td>
                    <td>
                        @can('parametro-show')
                        <a href="{{ route('parametros.show', $race->id) }}" class="btn btn-info" title="Visualizar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8ZM8 12.5c-2.485 0-4.5-2.015-4.5-4.5S5.515 3.5 8 3.5 12.5 5.515 12.5 8 10.485 12.5 8 12.5Zm0-7a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z"/>
                            </svg>
                        </a>
                        @endcan

                        @can('parametro-edit')
                        <a href="{{ route('parametros.edit', $race->id) }}" class="btn btn-primary" title="Actualizar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                                <path d="m13.498.795.149.149a1.207 1.207 0 0 1 0 1.707l-.82.82-1.707-1.707.82-.82a1.207 1.207 0 0 1 1.707 0ZM6.854 3.146 12.207 8.5l-6.854 6.854a1 1 0 0 1-.397.246l-4 1a1 1 0 0 1-1.212-1.212l1-4a1 1 0 0 1 .246-.397l6.854-6.854Z"/>
                            </svg>
                        </a>
                        @endcan

                        @can('parametro-delete')
    @if(!$activeEjemplars)
        <form id="delete-form-{{ $race->id }}" action="{{ route('parametros.destroy', $race->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-danger confirm-delete" data-id="{{ $race->id }}" title="Eliminar">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                    <path d="M6 1v1H3v1h10V2H9V1H6Zm0 3v8a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V4H6ZM4 4v8a3 3 0 0 0 3 3h2a3 3 0 0 0 3-3V4H4Z"/>
                </svg>
            </button>
        </form>
    @endif
@endcan


                        <!-- Botón de activar/desactivar -->
                        <form action="{{ route('parametros.toggleStatus', $race->id) }}" method="POST" style="display:inline;" >
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $activeEjemplars ? 'btn-primary' : 'btn-secondary' }}" title="Activar o Desactivar">
                                @if($activeEjemplars)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-toggle-on" viewBox="0 0 16 16">
                                        <path d="M11 2a5 5 0 1 1 0 10A5 5 0 0 1 11 2Zm0 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM5 4a5 5 0 1 1 0 10A5 5 0 0 1 5 4Z"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-toggle-off" viewBox="0 0 16 16">
                                        <path d="M5 2a5 5 0 1 0 0 10A5 5 0 0 0 5 2Zm0 8a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm6-8a5 5 0 1 0 0 10A5 5 0 0 0 11 2Z"/>
                                    </svg>
                                @endif
                            </button>
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $races->links() }}
</div>
</div>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const reiniciarBtn = document.getElementById("reiniciar-btn");
        const reiniciarForm = document.getElementById("reiniciar-form");

        reiniciarBtn.addEventListener("click", function (event) {
            event.preventDefault(); // Evita el envío automático del formulario

            Swal.fire({
                title: "¿Estás seguro?",
                text: "De Realizar Dicha Acción.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Confirmar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    reiniciarForm.submit(); // Envía el formulario si confirma
                }
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        const deleteButtons = document.querySelectorAll(".confirm-delete");

        deleteButtons.forEach(button => {
            button.addEventListener("click", function () {
                const raceId = this.getAttribute("data-id");
                const form = document.getElementById(`delete-form-${raceId}`);

                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Esta acción no se puede deshacer",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Enviar el formulario si el usuario confirma
                    }
                });
            });
        });
    });
</script>

@endsection
