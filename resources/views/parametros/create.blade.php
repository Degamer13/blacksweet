@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Asignar Ejemplares a Carreras</h2>

    <div id="alert-container"></div>

    <form action="{{ route('parametros.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="num_carreras">Número de Carreras</label>
            <input type="number" id="num_carreras" class="form-control" min="1" placeholder="Ingrese el número de carreras">
        </div>

        <div id="carreras_container"></div>

        <button type="submit" class="btn btn-success mt-3">Guardar</button>
    </form>
</div>

<!-- Modal para ver y gestionar los ejemplares de cada carrera -->
<div class="modal fade" id="modalEjemplares" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Ejemplares de la Carrera</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5><strong>Carrera:</strong> <span id="race_name_modal"></span></h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre del Ejemplar</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="ejemplares_table_body"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let ejemplaresData = {}; // Objeto para almacenar los ejemplares temporalmente
        let currentIndex = null; // Índice de la carrera actualmente abierta en el modal

        $('#num_carreras').on('input', function() {
            let numCarreras = $(this).val();
            let carrerasHtml = '';

            for (let i = 1; i <= numCarreras; i++) {
                carrerasHtml += `
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title">Carrera ${i}</h5>

                            <div class="form-group">
                                <label>Seleccione una carrera</label>
                                <select name="race_id[${i}]" class="form-control race-select" data-index="${i}" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($races as $race)
                                        <option value="{{ $race->id }}" data-name="{{ $race->name }}">{{ $race->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Nombre del Ejemplar</label>
                                <input type="text" class="form-control ejemplar_input" data-index="${i}" placeholder="Ingrese el nombre y presione Enter">
                            </div>

                            <button type="button" class="btn btn-info btn-sm ver-ejemplares" data-index="${i}" data-toggle="modal" data-target="#modalEjemplares">
                                Ver Ejemplares
                            </button>

                            <input type="hidden" name="ejemplares[${i}]" id="ejemplares_hidden_${i}">
                        </div>
                    </div>`;
            }

            $('#carreras_container').html(carrerasHtml);
        });

        // Capturar la entrada del ejemplar
        $(document).on('keypress', '.ejemplar_input', function(e) {
            if (e.which === 13) { // Enter
                e.preventDefault();
                let index = $(this).data('index');
                let nombreEjemplar = $(this).val().trim();

                if (nombreEjemplar !== '') {
                    if (!ejemplaresData[index]) {
                        ejemplaresData[index] = [];
                    }

                    ejemplaresData[index].push(nombreEjemplar);
                    $(`#ejemplares_hidden_${index}`).val(JSON.stringify(ejemplaresData[index]));
                    $(this).val('');
                }
            }
        });

        // Ver los ejemplares en el modal
        $(document).on('click', '.ver-ejemplares', function() {
            let index = $(this).data('index');
            currentIndex = index; // Guardamos el índice de la carrera actual
            let listaEjemplares = ejemplaresData[index] || [];

            // Obtener el nombre de la carrera seleccionada
            let raceName = $(`select[name="race_id[${index}]"] option:selected`).data('name') || 'No seleccionada';
            $('#race_name_modal').text(raceName);

            let tableContent = listaEjemplares.map((ejemplar, i) => `
                <tr data-id="${i}">
                    <td>${i + 1}</td>
                    <td><input type="text" class="form-control edit-ejemplar" value="${ejemplar}"></td>
                    <td>
                        <button class="btn btn-danger btn-sm eliminar-ejemplar">Eliminar</button>
                    </td>
                </tr>
            `).join('');

            $('#ejemplares_table_body').html(tableContent);
        });

        // Editar un ejemplar en la tabla
        $(document).on('input', '.edit-ejemplar', function() {
            let rowIndex = $(this).closest('tr').data('id');
            let newValue = $(this).val().trim();

            if (currentIndex !== null && ejemplaresData[currentIndex]) {
                ejemplaresData[currentIndex][rowIndex] = newValue;
                $(`#ejemplares_hidden_${currentIndex}`).val(JSON.stringify(ejemplaresData[currentIndex]));
            }
        });

        // Eliminar un ejemplar de la tabla
        $(document).on('click', '.eliminar-ejemplar', function() {
            let rowIndex = $(this).closest('tr').data('id');

            if (currentIndex !== null && ejemplaresData[currentIndex]) {
                ejemplaresData[currentIndex].splice(rowIndex, 1);
                $(`#ejemplares_hidden_${currentIndex}`).val(JSON.stringify(ejemplaresData[currentIndex]));

                $(this).closest('tr').remove();
            }
        });
    });
</script>
