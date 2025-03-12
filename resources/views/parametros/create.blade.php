@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Asignar Ejemplares a Carreras</h2>
    <!-- Alerta de error -->
    <div id="alert-container"></div>
    <form action="{{ route('parametros.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="num_carreras">Número de Carreras</label>
            <input type="number" id="num_carreras" class="form-control" min="1" placeholder="Ingrese el número de carreras">
        </div>

        <!-- Contenedor dinámico de las carreras -->
        <div id="carreras_container" style="max-height: 600px; overflow-y: auto; padding-right: 10px;"></div>

        <button type="submit" class="btn btn-success mt-3">Guardar</button>
    </form>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#num_carreras').on('input', function() {
            let numCarreras = $(this).val();
            let carrerasHtml = '';

            for (let i = 1; i <= numCarreras; i++) {
                carrerasHtml += `
                    <div class="card mt-3 shadow-sm rounded">
                        <div class="card-body">
                            <h5 class="card-title">Carrera ${i}</h5>
                            <div class="form-group">
                                <label>Seleccione una carrera</label>
                                <select name="race_id[${i}]" class="form-control" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($races as $race)
                                        <option value="{{ $race->id }}">{{ $race->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Número de Ejemplares</label>
                                <input type="number" class="form-control num_ejemplares" data-index="${i}" min="1" placeholder="Cantidad de ejemplares" required>
                            </div>
                            <div id="ejemplares_container_${i}"></div>
                        </div>
                    </div>`;
            }

            if (numCarreras == 0) {
                carrerasHtml = `<div class="alert alert-info">No se han agregado carreras aún. Ingrese un número para crear las carreras.</div>`;
            }

            $('#carreras_container').html(carrerasHtml);
        });

        $(document).on('input', '.num_ejemplares', function() {
            let numEjemplares = $(this).val();
            let index = $(this).data('index');
            let ejemplaresHtml = '';

            for (let j = 1; j <= numEjemplares; j++) {
                ejemplaresHtml += `
                    <div class="form-group">
                        <label>Nombre del ejemplar ${j}</label>
                        <input type="text" name="ejemplar_name[${index}][]" class="form-control" placeholder="Ingrese el nombre del ejemplar" required>
                    </div>`;
            }

            $(`#ejemplares_container_${index}`).html(ejemplaresHtml);
        });
    });
</script>

<style>
    #carreras_container {
        padding: 15px;
    }

    .card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
    }

    .card-body {
        padding: 20px;
    }

    .form-control {
        border-radius: 5px;
        padding: 10px;
    }

    .form-group label {
        font-weight: 600;
    }

    .btn-success {
        padding: 10px 20px;
        font-size: 16px;
    }
</style>
