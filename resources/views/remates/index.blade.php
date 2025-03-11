@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Lista de Remates</h1>

    <!-- Botón para crear un nuevo remate -->
    <a href="{{ route('remates.create') }}" class="btn btn-primary mb-3">Nuevo Remate</a>

    <!-- Formulario de búsqueda -->
    <form method="GET" class="mb-3">
        <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-secondary">Buscar</button>
    </form>

    <!-- Pestañas para filtrar por carreras -->
    <ul class="nav nav-tabs" id="raceTabs">
        @foreach($races as $race)
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#race{{ $race->id }}">{{ $race->name }}</a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content mt-3">
        @foreach($races as $race)
        <div class="tab-pane fade" id="race{{ $race->id }}">
            <table class="table">
                <thead>
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
                            <a href="{{ route('remates.edit', $remate) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('remates.destroy', $remate) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este remate?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
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
        @endforeach
    </div>

    <!-- Botón para calcular totales -->
    <button id="btn-totalizar" class="btn btn-success">Totalizar</button>

    {{ $remates->links() }}
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Activar la primera pestaña por defecto
        $('.nav-tabs a:first').addClass('active');
        $('.tab-content .tab-pane:first').addClass('show active');

        // Función para calcular los totales
        $('#btn-totalizar').click(function () {
            // Obtener la pestaña activa
            var activeTab = $('.nav-tabs .nav-link.active').attr('href');
            var activeTable = $(activeTab).find('table');

            let totalM1 = 0, totalM2 = 0, totalM3 = 0, totalM4 = 0;

            // Calcular los totales solo para la tabla activa
            activeTable.find('.monto1').each(function () { totalM1 += parseFloat($(this).text()) || 0; });
            activeTable.find('.monto2').each(function () { totalM2 += parseFloat($(this).text()) || 0; });
            activeTable.find('.monto3').each(function () { totalM3 += parseFloat($(this).text()) || 0; });
            activeTable.find('.monto4').each(function () { totalM4 += parseFloat($(this).text()) || 0; });

            // Actualizar los totales en la tabla activa
            $(activeTab).find('#subasta1').text(totalM1);
            $(activeTab).find('#subasta2').text(totalM2);
            $(activeTab).find('#subasta3').text(totalM3);
            $(activeTab).find('#subasta4').text(totalM4);

            let totalSubasta = totalM1 + totalM2 + totalM3 + totalM4;
            $(activeTab).find('#total_subasta').text(totalSubasta);

            let porcentaje = totalSubasta * 0.7; // -30%
            $(activeTab).find('#porcentaje').text(porcentaje.toFixed(2));

            let pote = parseFloat($(activeTab).find('.pote:first').text()) || 0;
            $(activeTab).find('#pote_value').text(pote.toFixed(2));

            let totalPagar = porcentaje + pote;
            $(activeTab).find('#total_pagar').text(totalPagar.toFixed(2));
        });
    });
</script>
@endsection
