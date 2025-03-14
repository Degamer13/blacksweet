@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Registrar Remate</h2>

        <form action="{{ route('remates.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="number">Número:</label>
                <input type="number" class="form-control" name="number" id="number" required>
            </div>

            <div class="form-group">
                <label for="race_id">Seleccionar Carrera:</label>
                <select name="race_id" id="race_id" class="form-control" required>
                    <option value="">Seleccione una Carrera</option>
                    @foreach($races as $race)
                        <option value="{{ $race->id }}">{{ $race->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="ejemplar_name">Seleccionar Ejemplar:</label>
                <select name="ejemplar_name" id="ejemplar_name" class="form-control" required>
                    <option value="">Seleccione un Ejemplar</option>
                </select>
                <span id="mensaje-validacion" style="font-weight: bold;"></span>
            </div>

            <div class="form-group">
                <label for="cliente">Cliente:</label>
                <input type="text" class="form-control" name="cliente" id="cliente" required>
            </div>

            <div class="form-group">
                <label for="monto1">Monto 1:</label>
                <input type="number" class="form-control" name="monto1" id="monto1" required>
            </div>

            <div class="form-group">
                <label for="monto2">Monto 2:</label>
                <input type="text" class="form-control" name="monto2" id="monto2" disabled>
            </div>

            <div class="form-group">
                <label for="monto3">Monto 3:</label>
                <input type="text" class="form-control" name="monto3" id="monto3" disabled>
            </div>

            <div class="form-group">
                <label for="monto4">Monto 4:</label>
                <input type="text" class="form-control" name="monto4" id="monto4" disabled>
            </div>

            <div class="form-group">
                <label for="pote">Pote:</label>
                <input type="number" required class="form-control" name="pote" id="pote">
            </div>

            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>
<!-- Incluir el CSS de Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Incluir el JS de Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Cargar los ejemplares en función de la carrera seleccionada
            $('#race_id').change(function () {
                let raceId = $(this).val();
                if (raceId) {
                    $.ajax({
                        url: `/ejemplares/${raceId}`,
                        type: 'GET',
                        success: function (data) {
                            $('#ejemplar_name').html('<option value="">Seleccione un Ejemplar</option>');
                            data.forEach(function (ejemplar) {
                                $('#ejemplar_name').append(`<option value="${ejemplar.name}">${ejemplar.name}</option>`);
                            });
                        }
                    });
                } else {
                    $('#ejemplar_name').html('<option value="">Seleccione un Ejemplar</option>');
                }
            });

            // Validar si el ejemplar ya está registrado
            $('#ejemplar_name').change(function () {
                let ejemplar_name = $(this).val();
                if (ejemplar_name) {
                    $.ajax({
                        url: `/validar-ejemplar/${ejemplar_name}`,
                        type: 'GET',
                        success: function (response) {
                            $('#mensaje-validacion').text(response.message).css('color', 'green');
                        },
                        error: function (xhr) {
                            $('#mensaje-validacion').text(xhr.responseJSON.message).css('color', 'red');
                        }
                    });
                } else {
                    $('#mensaje-validacion').text('');
                }
            });

            // Calcular montos automáticamente
            $('#monto1').on('input', function () {
                let monto1 = parseFloat($(this).val());
                if (!isNaN(monto1) && monto1 > 0) {
                    let monto2 = Math.ceil((monto1 / 2));
                    let monto3 = Math.ceil((monto2 / 2));
                    let monto4 = Math.ceil((monto3 / 2) / 5) * 5;

                    $('#monto2').val(monto2);
                    $('#monto3').val(monto3);
                    $('#monto4').val(monto4);
                } else {
                    $('#monto2').val('');
                    $('#monto3').val('');
                    $('#monto4').val('');
                }
            });
        });
    </script>
@endsection
