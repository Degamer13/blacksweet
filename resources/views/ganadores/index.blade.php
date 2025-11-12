@extends(Auth::user()->hasRole('ventas') || Auth::user()->hasRole('usuarios') ? 'layouts.app1' : 'layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-9">
            <h3>Ganadores</h3>
        </div>
    </div>

    @can('ganador-create')
        <a href="{{ route('ganadores.create') }}" class="btn btn-primary mb-3">Agregar Ganador</a>
    @endcan

    @can('ganador-search')
        <div class="col-12 col-md-6 mb-3">
            <form>
                <div class="input-group">
                    <input type="text" class="form-control" name="buscarpor" value="{{ $buscarpor ?? '' }}"
                           placeholder="Buscar ejemplar o cliente" aria-label="Buscar" aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-success" id="button-addon2" type="submit">
                            <!-- Lupa SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
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
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Carrera</th>
                    <th>Ejemplar</th>
                    <th>Cliente</th>
                    <th>Ganador</th>
                    <th>Posición</th>
                    <th>Monto Ganado</th>
                    <th>Porcentaje</th>
                    <th width="150px">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($ganadores as $ganador)
                <tr>
                    <td>{{ $ganador->remate->race_id }}</td>
                    <td>{{ $ganador->remate->ejemplar_name }}</td>
                    <td>{{ $ganador->remate->cliente }}</td>
                    <td>
                        @if($ganador->es_ganador)
                            <span class="badge bg-success">✅ Sí</span>
                        @else
                            <span class="badge bg-danger">❌ No</span>
                        @endif
                    </td>
                    <td>{{ $ganador->posicion ?? '-' }}</td>
                    <td>
                        @if($ganador->es_ganador)
                            {{ number_format($ganador->monto_ganado, 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($ganador->es_ganador)
                            {{ number_format($ganador->porcentaje, 2) }}%
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @can('ganador-show')
                            <a class="btn btn-info btn-sm" href="{{ route('ganadores.show', $ganador->id) }}" title="Visualizar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8ZM8 12.5c-2.485 0-4.5-2.015-4.5-4.5S5.515 3.5 8 3.5 12.5 5.515 12.5 8 10.485 12.5 8 12.5Zm0-7a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z"/>
                            </svg>
                            </a>
                        @endcan
                        @can('ganador-show')
    <a class="btn btn-secondary btn-sm" href="{{ route('ganadores.pdf', $ganador->id) }}" title="Generar PDF" target="_blank">
        <!-- Icono PDF -->
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
            <path d="M5.5 6.5v3h1v-3h-1zm3 0v3h1v-1h1v-2h-2z"/>
            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3.5-.5V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5H10.5a.5.5 0 0 1-.5-.5z"/>
        </svg>
    </a>
@endcan

                        @can('ganador-edit')
                            <a class="btn btn-primary btn-sm" href="{{ route('ganadores.edit', $ganador->id) }}" title="Actualizar">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                                <path d="m13.498.795.149.149a1.207 1.207 0 0 1 0 1.707l-.82.82-1.707-1.707.82-.82a1.207 1.207 0 0 1 1.707 0ZM6.854 3.146 12.207 8.5l-6.854 6.854a1 1 0 0 1-.397.246l-4 1a1 1 0 0 1-1.212-1.212l1-4a1 1 0 0 1 .246-.397l6.854-6.854Z"/>
                            </svg>
                            </a>
                        @endcan

                        @can('ganador-delete')
                            <form method="POST" id="delete-form-{{ $ganador->id }}"
                                  action="{{ route('ganadores.destroy', $ganador->id) }}"
                                  style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm confirm-delete" data-id="{{ $ganador->id }}" title="Eliminar">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                    <path d="M6 1v1H3v1h10V2H9V1H6Zm0 3v8a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V4H6ZM4 4v8a3 3 0 0 0 3 3h2a3 3 0 0 0 3-3V4H4Z"/>
                </svg>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {!! $ganadores->links() !!}
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".confirm-delete");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function () {
            const ganadorId = this.getAttribute("data-id");
            const form = document.getElementById(`delete-form-${ganadorId}`);

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
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
