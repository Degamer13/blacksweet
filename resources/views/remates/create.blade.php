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
                <label for="ejemplar_id">Seleccionar Ejemplar:</label>
                <select name="ejemplar_id" id="ejemplar_id" class="form-control" required>
                    <option value="">Seleccione un Ejemplar</option>
                </select>
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
                <input type="number" class="form-control" name="pote" id="pote">
            </div>

            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>

    <script>
        // Esta función actualiza los ejemplares en función de la carrera seleccionada
        document.getElementById('race_id').addEventListener('change', function() {
            var raceId = this.value;
            fetch(`/ejemplares/${raceId}`)
                .then(response => response.json())
                .then(data => {
                    var ejemplarSelect = document.getElementById('ejemplar_id');
                    ejemplarSelect.innerHTML = '<option value="">Seleccione un Ejemplar</option>';
                    data.forEach(function(ejemplar) {
                        ejemplarSelect.innerHTML += `<option value="${ejemplar.id}">${ejemplar.name}</option>`;
                    });
                });
        });

        // Calculamos los montos automáticamente
        document.getElementById('monto1').addEventListener('input', function() {
            var monto1 = parseFloat(this.value);
            if (!isNaN(monto1)) {
                document.getElementById('monto2').value = Math.floor(monto1 / 2);
                var monto2 = parseFloat(document.getElementById('monto2').value);
                document.getElementById('monto3').value = Math.floor(monto2 / 2);
                var monto3 = parseFloat(document.getElementById('monto3').value);
                document.getElementById('monto4').value = Math.floor(monto3 / 2);
            }
        });
    </script>
@endsection
