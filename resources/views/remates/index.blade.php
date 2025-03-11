@extends('layouts.admin')

@section('content')
<style>
    .table-bordered{
        border: 5px solid #47bb19;
    }
</style>
<div class="container">
    <h1 class="text-center">Lista de Remates</h1>

    <!-- Botón para crear un nuevo remate -->
    <a href="{{ route('remates.create') }}" class="btn btn-primary mb-3">Nuevo Remate</a>

    <!-- Formulario de búsqueda -->
    <div class="col-12 col-md-6 mb-3">
        <form method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control me-2" placeholder="Buscar..." value="{{ request('search') }}">
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

    <!-- Pestañas para filtrar por carreras -->
    <ul class="nav nav-tabs" id="raceTabs">
        @foreach($races as $race)
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#race{{ $race->id }}">{{ $race->name }}</a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content mt-3">
        @foreach($races as $race)
        <div class="tab-pane fade" id="race{{ $race->id }}">
            <!-- Agregar campo oculto para race_id -->
            <input type="hidden" class="race-id" value="{{ $race->id }}">

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Nro</th>
                            <th>Ejemplar</th>
                            <th>Monto1</th>
                            <th>Monto2</th>
                            <th>Monto3</th>
                            <th>Monto4</th>
                            <th>Cliente</th>
                            <th>Monto</th>
                            <th>Pote</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($remates->where('race_id', $race->id) as $remate)
                        <tr>
                            <td>{{ $remate->number }}</td>
                            <td>{{ $remate->ejemplar->name }}</td>
                            <td class="monto1">{{ $remate->monto1 }}</td>
                            <td class="monto2">{{ $remate->monto2 }}</td>
                            <td class="monto3">{{ $remate->monto3 }}</td>
                            <td class="monto4">{{ $remate->monto4 }}</td>
                            <td>{{ $remate->cliente }}</td>
                            <td>{{ $remate->total }}</td>
                            <td class="pote">{{ $remate->pote }}</td>
                            <td>
                                <a href="{{ route('remates.edit', $remate->id) }}" class="btn btn-warning btn-sm">Editar</a>

                                <form action="{{ route('remates.destroy', $remate) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este remate?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="2">Totales</th>
                            <th id="subasta1">0</th>
                            <th id="subasta2">0</th>
                            <th id="subasta3">0</th>
                            <th id="subasta4">0</th>
                            <th colspan="4"></th>
                        </tr>
                        <tr>
                            <th colspan="3">Total Subasta</th>
                            <th colspan="3" id="total_subasta">0</th>
                            <th colspan="4"></th>
                        </tr>
                        <tr>
                            <th colspan="3">Porcentaje (-30%)</th>
                            <th colspan="3" id="porcentaje">0</th>
                            <th colspan="4"></th>
                        </tr>
                        <tr>
                            <th colspan="3">Pote</th>
                            <th colspan="3" id="pote_value">0</th>
                            <th colspan="4"></th>
                        </tr>
                        <tr>
                            <th colspan="3">Total a Pagar</th>
                            <th colspan="3" id="total_pagar">0</th>
                            <th colspan="4"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Botón para calcular totales -->
    <button id="btn-totalizar" class="btn btn-success">Totalizar</button>

    <div class="mt-3">
        {{ $remates->links() }}
    </div>
</div>

<!-- jQuery y Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        // Activar la primera pestaña por defecto
        $('.nav-tabs a:first').addClass('active');
        $('.tab-content .tab-pane:first').addClass('show active');

        // Función para calcular los totales
        $('#btn-totalizar').click(function () {
            var activeTab = $('.nav-tabs .nav-link.active').attr('href');
            var activeTable = $(activeTab).find('table');

            // Variables para almacenar los totales por monto
            let totalM1 = 0, totalM2 = 0, totalM3 = 0, totalM4 = 0;

            // Recorremos cada monto en las celdas correspondientes
            activeTable.find('.monto1').each(function () { totalM1 += parseFloat($(this).text()) || 0; });
            activeTable.find('.monto2').each(function () { totalM2 += parseFloat($(this).text()) || 0; });
            activeTable.find('.monto3').each(function () { totalM3 += parseFloat($(this).text()) || 0; });
            activeTable.find('.monto4').each(function () { totalM4 += parseFloat($(this).text()) || 0; });

            // Asignar los valores a las celdas de la tabla para cada subasta
            $(activeTab).find('#subasta1').text(totalM1);
            $(activeTab).find('#subasta2').text(totalM2);
            $(activeTab).find('#subasta3').text(totalM3);
            $(activeTab).find('#subasta4').text(totalM4);

            // Calcular el total de la subasta
            let totalSubasta = totalM1 + totalM2 + totalM3 + totalM4;
            $(activeTab).find('#total_subasta').text(totalSubasta);

            // Calcular el porcentaje (30%)
            let porcentaje = totalSubasta * 0.3;
            $(activeTab).find('#porcentaje').text(porcentaje.toFixed(2));

            // Obtener el valor del pote
            let pote = parseFloat($(activeTab).find('.pote:first').text()) || 0;
            $(activeTab).find('#pote_value').text(pote.toFixed(2));

            // Calcular el total a pagar
            let totalPagar = porcentaje + pote;
            $(activeTab).find('#total_pagar').text(totalPagar.toFixed(2));

            // Obtener el ID de la carrera activa
            var raceId = $(activeTab).find('.race-id').val(); // Ahora el race_id estará presente

            // Enviar los datos de los totales a la base de datos via Ajax
            $.ajax({
                url: "{{ route('remates.actualizarRemate') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    race_id: raceId,  // Asegúrate de que raceId esté presente
                    totalM1: totalM1,
                    totalM2: totalM2,
                    totalM3: totalM3,
                    totalM4: totalM4,
                    totalSubasta: totalSubasta,
                    porcentaje: porcentaje,
                    pote: pote,
                    totalPagar: totalPagar
                },
                success: function(response) {
                    alert('Totales actualizados correctamente');
                },
                error: function(xhr, status, error) {
                    alert('Ocurrió un error al actualizar los totales: ' + xhr.responseJSON.message);  // Mostrar el mensaje del error
                }
            });
        });
    });
</script>
@endsection
