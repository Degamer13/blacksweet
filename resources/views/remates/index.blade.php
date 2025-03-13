@extends(Auth::user()->hasRole('ventas') ? 'layouts.app1' : 'layouts.admin')

@section('content')
    <style>
        .container {
            margin-top: 30px;
        }
        .table-bordered {
            border: 5px solid #47bb19;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .nav-tabs .nav-link.active {
            background-color: #47bb19;
            color: white;
            border-color: #47bb19;
        }
        .table-dark th {
            background-color: #343a40;
            color: white;
        }
        tfoot tr th {
            font-weight: bold;
        }
        .alert {
            margin-top: 20px;
        }
    </style>

    <!-- El formulario se envía a la ruta remates.store -->
    <form id="remateForm" action="{{ route('remates.store') }}" method="POST">
        @csrf
        <div class="container">
            <h3 class="text-center">Lista de Remates</h3>

            <!-- Pestañas para cada race_id -->
            <ul class="nav nav-tabs" id="raceTabs">
                @foreach($ejemplares as $race_id => $ejemplarGroup)
                    <li class="nav-item">
                        <a class="nav-link @if($loop->first) active @endif" id="race-{{ $race_id }}-tab" data-bs-toggle="tab" href="#race-{{ $race_id }}">{{ 'Carrera ' . $race_id }}</a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content mt-3">
                @foreach($ejemplares as $race_id => $ejemplarGroup)
                    <div class="tab-pane fade @if($loop->first) show active @endif" id="race-{{ $race_id }}">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nro</th>  <!-- Número incremental -->
                                        <th>Ejemplar</th>
                                        <th>Monto1</th>
                                        <th>Monto2</th>
                                        <th>Monto3</th>
                                        <th>Monto4</th>
                                        <th>Cliente</th>
                                        <th>Monto</th>
                                        <th>Pote</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ejemplarGroup as $index => $ejemplar)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <!-- Se muestran los datos; para enviar se usan inputs (algunos readonly) -->
                                            <td>
                                                <input type="text" name="ejemplar_name[]" class="form-control ejemplar_name" 
                                                    value="{{ $ejemplar->ejemplar_name }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="monto1[]" class="form-control monto1" value="0">
                                            </td>
                                            <td>
                                                <input type="number" name="monto2[]" class="form-control monto2" value="0" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="monto3[]" class="form-control monto3" value="0" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="monto4[]" class="form-control monto4" value="0" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="cliente[]" class="form-control cliente" 
                                                    value="{{ $ejemplar->cliente ?? 'N/A' }}">
                                            </td>
                                            <td>
                                                <input type="number" name="total[]" class="form-control monto" value="0" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="pote[]" class="form-control pote" value="0">
                                            </td>
                                            <!-- Enviar también el race_id para cada fila -->
                                            <input type="hidden" name="race_id[]" value="{{ $race_id }}">
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
                                        <th colspan="3"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Total Subasta</th>
                                        <th colspan="3" id="total_subasta">0</th>
                                        <th colspan="3"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Porcentaje (-30%)</th>
                                        <th colspan="3" id="porcentaje">0</th>
                                        <th colspan="3"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Pote</th>
                                        <th colspan="3" id="pote_value">0</th>
                                        <th colspan="3"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Total a Pagar</th>
                                        <th colspan="3" id="total_pagar">0</th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Botone: Enviar formulario -->
           
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>

    <!-- Incluimos jQuery y Bootstrap Bundle -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function () {
        // Activar la primera pestaña por defecto (si no lo hace Bootstrap automáticamente)
        $('.nav-tabs a:first').addClass('active');
        $('.tab-content .tab-pane:first').addClass('show active');

        // Mover el foco al siguiente input al presionar Enter
        $(document).on('keydown', 'input', function (e) {
            if (e.key === "Enter") {
                // Buscar el siguiente input en la misma columna de la siguiente fila
                var nextInput = $(this).closest('tr').next('tr').find('input').first();
                if (nextInput.length > 0) {
                    nextInput.focus();
                    e.preventDefault(); // evitar el salto de línea por defecto
                }
            }
        });

        // Al cambiar el valor de Monto1 se actualizan los montos y el total de la fila
        $(document).on('input', '.monto1', function () {
            var monto1 = parseFloat($(this).val()) || 0;
            var monto2 = monto1 / 2;
            var monto3 = monto2 / 2;
            var monto4 = monto3 / 2;
            var totalMonto = monto1 + monto2 + monto3 + monto4;

            $(this).closest('tr').find('.monto2').val(monto2.toFixed(2));
            $(this).closest('tr').find('.monto3').val(monto3.toFixed(2));
            $(this).closest('tr').find('.monto4').val(monto4.toFixed(2));
            $(this).closest('tr').find('.monto').val(totalMonto.toFixed(2));

            actualizarTotales();
        });

        // Al cambiar el valor de Pote se replica en todas las filas del mismo race_id
        $(document).on('input', '.pote', function () {
            var poteValue = $(this).val();
            // Para identificar la fila, supongamos que cada fila tiene un input hidden con clase "race-id"
            var raceId = $(this).closest('tr').find('input[name="race_id[]"]').val();

            // Recorrer todas las filas y actualizar las casillas de pote del mismo race_id
            $('table tr').each(function () {
                if ($(this).find('input[name="race_id[]"]').val() === raceId) {
                    $(this).find('.pote').val(poteValue);
                }
            });
            actualizarTotales();
        });


        function actualizarTotales() {
            var totalM1 = 0, totalM2 = 0, totalM3 = 0, totalM4 = 0;
            var totalSubasta = 0;
            var firstPote = null;

            // Recorrer cada fila de la tabla (por cada input total)
            $('.monto1').each(function () {
                totalM1 += parseFloat($(this).val()) || 0;
            });
            $('.monto2').each(function () {
                totalM2 += parseFloat($(this).val()) || 0;
            });
            $('.monto3').each(function () {
                totalM3 += parseFloat($(this).val()) || 0;
            });
            $('.monto4').each(function () {
                totalM4 += parseFloat($(this).val()) || 0;
            });

            totalSubasta = totalM1 + totalM2 + totalM3 + totalM4;

            // Para el pote, usamos el valor de la primera casilla encontrada de la columna (por fila)
            $('.pote').each(function () {
                if (firstPote === null) {
                    firstPote = parseFloat($(this).val()) || 0;
                }
            });
            if(firstPote === null){
                firstPote = 0;
            }

            // Calcular el 30%
            var porcentaje = totalSubasta * 0.30;
            var totalSubastaMenos30 = totalSubasta - porcentaje;
            var totalPagar = totalSubastaMenos30 + firstPote;

            // Actualizar totales en el pie de la tabla (estos elementos deben existir en el <tfoot>)
            $('#subasta1').text(totalM1.toFixed(2));
            $('#subasta2').text(totalM2.toFixed(2));
            $('#subasta3').text(totalM3.toFixed(2));
            $('#subasta4').text(totalM4.toFixed(2));
            $('#total_subasta').text(totalSubasta.toFixed(2));
            $('#porcentaje').text(porcentaje.toFixed(2));
            $('#pote_value').text(firstPote.toFixed(2));
            $('#total_pagar').text(totalPagar.toFixed(2));
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('success') == 'subasta_finalizada')
                Swal.fire({
                    title: '¡Subasta Finalizada!',
                    text: 'Los datos han sido guardados correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
            @endif
        });
    </script>
    
@endsection
