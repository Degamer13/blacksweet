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
        // Al cambiar el número de carreras, se generan las tarjetas de las carreras
        $('#num_carreras').on('input', function() {
            let numCarreras = $(this).val();
            let carrerasHtml = '';
            
            // Creación de las tarjetas para cada carrera
            for (let i = 1; i <= numCarreras; i++) {
                carrerasHtml += `
                    <div class="card mt-3 shadow-sm rounded">
                        <div class="card-body">
                            <h5 class="card-title">Carrera ${i}</h5>
                            <div class="form-group">
                                <label>Seleccione una carrera</label>
                                <select name="race_id[${i}][]" class="form-control race-select" data-index="${i}" required>
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

            // Si no hay carreras, se muestra un mensaje explicativo
            if (numCarreras == 0) {
                carrerasHtml = `<div class="alert alert-info">No se han agregado carreras aún. Ingrese un número para crear las carreras.</div>`;
            }

            // Actualizamos el contenedor con las nuevas tarjetas
            $('#carreras_container').html(carrerasHtml);
        });

        // Al cambiar el número de ejemplares, se crean los selectores de ejemplares correspondientes
        $(document).on('input', '.num_ejemplares', function() {
            let numEjemplares = $(this).val();
            let index = $(this).data('index');
            let ejemplaresHtml = '';
            
            // Creación de los campos para seleccionar los ejemplares
            for (let j = 1; j <= numEjemplares; j++) {
                ejemplaresHtml += `
                    <div class="form-group">
                        <label>Seleccione un ejemplar ${j}</label>
                        <select name="ejemplars[${index}][]" class="form-control" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach($ejemplars as $ejemplar)
                                <option value="{{ $ejemplar->id }}">{{ $ejemplar->name }}</option>
                            @endforeach
                        </select>
                    </div>`;
            }

            // Actualizamos el contenedor de ejemplares correspondiente a esta carrera
            $(`#ejemplares_container_${index}`).html(ejemplaresHtml);
        });
    });
</script>


<style>
    /* Estilo para el contenedor de carreras para mejorar la experiencia visual */
    #carreras_container {
        padding: 15px;
    }

    /* Estilo adicional para las tarjetas de carrera */
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
    }

    .card-body {
        padding: 20px;
    }

    /* Mejorar el espaciado y bordes de los campos */
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
