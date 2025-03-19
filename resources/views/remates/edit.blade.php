@extends(Auth::user()->hasRole('ventas') ? 'layouts.app1' : 'layouts.admin')

@section('content')
   <style>
       .nav-tabs .nav-link.active {
           background-color: #6a994e;
           color: white;
           border-color: #6a994e;
           font-size: 20px;
       }
       .table-dark th {
           background-color: #3d405b;
           color: white;
           font-size: 20px;
       }
       table tbody input.form-control {
           font-weight: bold !important;
       }
       tfoot tr th {
           font-weight: bold;
           background-color: #ddb892;
           font-size: 20px;
       }
   </style>

   <form id="remateForm" action="{{ route('remates.update', $remate->id) }}" method="POST">
       @csrf
       @method('PUT')
       <div class="container">
           <h3 class="text-center">Editar Remates</h3>
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
                                       <th>Nro</th>
                                       <th>Ejemplar</th>
                                       <th>Monto1</th>
                                       <th>Monto2</th>
                                       <th>Monto3</th>
                                       <th>Monto4</th>
                                       <th>Cliente</th>
                                       <th>Monto</th>
                                       <th>Pote</th>
                                       <th>Acumulado</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   @foreach($ejemplarGroup as $index => $ejemplar)
                                       <tr>
                                           <td>{{ $index + 1 }}</td>
                                           <td>
                                               <input type="text" name="ejemplar_name[]" class="form-control" value="{{ $ejemplar->ejemplar_name }}" readonly>
                                           </td>
                                           <td><input type="number" name="monto1[]" class="form-control monto1" value="{{ $ejemplar->monto1 }}"></td>
                                           <td><input type="number" name="monto2[]" class="form-control monto2" value="{{ $ejemplar->monto2 }}"></td>
                                           <td><input type="number" name="monto3[]" class="form-control monto3" value="{{ $ejemplar->monto3 }}"></td>
                                           <td><input type="number" name="monto4[]" class="form-control monto4" value="{{ $ejemplar->monto4 }}"></td>
                                           <td><input type="text" name="cliente[]" class="form-control" value="{{ $ejemplar->cliente }}"></td>
                                           <td><input type="number" name="total[]" class="form-control monto" value="{{ $ejemplar->total }}" readonly></td>
                                           <td><input type="number" name="pote[]" class="form-control pote" value="{{ $ejemplar->pote }}"></td>
                                           <td><input type="number" name="acumulado[]" class="form-control acumulado" value="{{ $ejemplar->acumulado }}"></td>
                                       </tr>
                                   @endforeach
                               </tbody>
                           </table>
                       </div>
                   </div>
               @endforeach
           </div>
           <button type="submit" class="btn btn-primary">Actualizar</button>
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
            var nextInput = $(this).closest('tr').next('tr').find('input').first();
            if (nextInput.length > 0) {
                nextInput.focus();
                e.preventDefault();
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

    // Escucha cambios en cualquiera de los montos
    $(document).on('input', '.monto2, .monto3, .monto4', function () {
        var row = $(this).closest('tr');

        var monto1 = parseFloat(row.find('.monto1').val()) || 0;
        var monto2 = parseFloat(row.find('.monto2').val()) || 0;
        var monto3 = parseFloat(row.find('.monto3').val()) || 0;
        var monto4 = parseFloat(row.find('.monto4').val()) || 0;
        var totalMonto = monto1 + monto2 + monto3 + monto4;

        row.find('.monto').val(totalMonto.toFixed(2));

        actualizarTotales();
    });

    // Al cambiar el valor de Pote, se replica en todas las filas del mismo race_id
    $(document).on('input', '.pote', function () {
        var poteValue = $(this).val();
        var raceId = $(this).closest('tr').find('input[name="race_id[]"]').val();

        $('table tr').each(function () {
            if ($(this).find('input[name="race_id[]"]').val() === raceId) {
                $(this).find('.pote').val(poteValue);
            }
        });
        actualizarTotales();
    });

    // Al cambiar el valor de Acumulado, se replica en todas las filas del mismo race_id
   $(document).on('input', '.acumulado', function () {
    var acumuladoValue = $(this).val();
    var raceId = $(this).closest('tr').find('input[name="race_id[]"]').val();

    $('table tr').each(function () {
        if ($(this).find('input[name="race_id[]"]').val() === raceId) {
            $(this).find('.acumulado').val(acumuladoValue);
        }
    });
    actualizarTotales();
});

    function actualizarTotales() {
        var totalM1 = 0, totalM2 = 0, totalM3 = 0, totalM4 = 0;
        var totalSubasta = 0;
        var firstPote = null;
        var firstAcumulado = null; // Inicialización de firstAcumulado

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

        $('.pote').each(function () {
            if (firstPote === null) {
                firstPote = parseFloat($(this).val()) || 0;
            }
        });
        if (firstPote === null) {
            firstPote = 0;
        }

        $('.acumulado').each(function () {
            if (firstAcumulado === null) {
                firstAcumulado = parseFloat($(this).val()) || 0;
            }
        });
        if (firstAcumulado === null) {
            firstAcumulado = 0;
        }

        var porcentaje = totalSubasta * 0.30;
        var totalSubastaMenos30 = totalSubasta - porcentaje;
        var totalPagar = totalSubastaMenos30 + firstPote + firstAcumulado; // Agregar acumulado en totalPagar

        // Actualizar los valores de las celdas
        $('#subasta1').text(totalM1.toFixed(2));
        $('#subasta2').text(totalM2.toFixed(2));
        $('#subasta3').text(totalM3.toFixed(2));
        $('#subasta4').text(totalM4.toFixed(2));
        $('#total_subasta').text(totalSubasta.toFixed(2));

        $('#porcentaje_hidden').val(porcentaje.toFixed(2));

        $('#pote_value').text(firstPote.toFixed(2));
        $('#acumulado_value').text(firstAcumulado.toFixed(2)); // Mostrar acumulado
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
