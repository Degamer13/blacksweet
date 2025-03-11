@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Editar Remate</h2>

        <form action="{{ route('remates.update', $remate->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="number">Número:</label>
                <input type="number" class="form-control" name="number" id="number" value="{{ old('number', $remate->number) }}" required>
            </div>

            <div class="form-group">
                <label for="race_id">Seleccionar Carrera:</label>
                <select name="race_id" id="race_id" class="form-control" required>
                    <option value="">Seleccione una Carrera</option>
                    @foreach($races as $race)
                        <option value="{{ $race->id }}" {{ old('race_id', $remate->race_id) == $race->id ? 'selected' : '' }}>{{ $race->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="ejemplar_id">Seleccionar Ejemplar:</label>
                <select name="ejemplar_id" id="ejemplar_id" class="form-control" required>
                    <option value="">Seleccione un Ejemplar</option>
                    @if($remate->race->ejemplars->isNotEmpty())
                        @foreach($remate->race->ejemplars as $ejemplar)
                            <option value="{{ $ejemplar->id }}" {{ old('ejemplar_id', $remate->ejemplar_id) == $ejemplar->id ? 'selected' : '' }}>{{ $ejemplar->name }}</option>
                        @endforeach
                    @else
                        <option value="">No hay ejemplares disponibles</option>
                    @endif
                </select>
                <span id="mensaje-validacion" style="font-weight: bold;"></span>
            </div>

            <div class="form-group">
                <label for="cliente">Cliente:</label>
                <input type="text" class="form-control" name="cliente" id="cliente" value="{{ old('cliente', $remate->cliente) }}" required>
            </div>

            <div class="form-group">
                <label for="monto1">Monto 1:</label>
                <input type="number" class="form-control" name="monto1" id="monto1" value="{{ old('monto1', $remate->monto1) }}" required>
            </div>

            <div class="form-group">
                <label for="monto2">Monto 2:</label>
                <input type="text" class="form-control" name="monto2" id="monto2" value="{{ old('monto2', $remate->monto2) }}" disabled>
            </div>

            <div class="form-group">
                <label for="monto3">Monto 3:</label>
                <input type="text" class="form-control" name="monto3" id="monto3" value="{{ old('monto3', $remate->monto3) }}" disabled>
            </div>

            <div class="form-group">
                <label for="monto4">Monto 4:</label>
                <input type="text" class="form-control" name="monto4" id="monto4" value="{{ old('monto4', $remate->monto4) }}" disabled>
            </div>

            <div class="form-group">
                <label for="pote">Pote:</label>
                <input type="number" class="form-control" name="pote" id="pote" value="{{ old('pote', $remate->pote) }}">
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>

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
                            $('#ejemplar_id').html('<option value="">Seleccione un Ejemplar</option>');
                            data.forEach(function (ejemplar) {
                                $('#ejemplar_id').append(`<option value="${ejemplar.id}" ${ejemplar.id == "{{ old('ejemplar_id', $remate->ejemplar_id) }}" ? 'selected' : ''}>${ejemplar.name}</option>`);
                            });
                        }
                    });
                } else {
                    $('#ejemplar_id').html('<option value="">Seleccione un Ejemplar</option>');
                }
            });

            // Validar si el ejemplar ya está registrado
            $('#ejemplar_id').change(function () {
                let ejemplar_id = $(this).val();
                if (ejemplar_id) {
                    $.ajax({
                        url: `/validar-ejemplar/${ejemplar_id}`,
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
                if (!isNaN(monto1)) {
                    let monto2 = Math.ceil((monto1 / 2));
                    let monto3 = Math.ceil((monto2 / 2));
                    let monto4 = Math.ceil((monto3 / 2) / 5) * 5;

                    $('#monto2').val(monto2);
                    $('#monto3').val(monto3);
                    $('#monto4').val(monto4);
                } else {
                    // Si monto1 no es un número, vaciar los montos dependientes
                    $('#monto2').val('');
                    $('#monto3').val('');
                    $('#monto4').val('');
                }
            });
        });
    </script>
@endsection
