@extends(Auth::user()->hasRole('ventas') || Auth::user()->hasRole('usuarios') ? 'layouts.app1' : 'layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Registrar Ganador</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Oops!</strong> Hubo algunos problemas con los datos:<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ganadores.store') }}" method="POST" id="form-ganadores">
        @csrf

        {{-- Selección de múltiples remates para empate --}}
        <div class="mb-3">
            <label for="remate_id" class="form-label">Ganadores (Carrera - Ejemplar - Cliente)</label>
            <select name="remate_ids[]" id="remate_id" class="form-control" multiple required>
                @foreach($remates as $remate)
                    <option value="{{ $remate->id }}">
                        Carrera {{ $remate->race_id }} - {{ $remate->ejemplar_name }} ({{ $remate->cliente }})
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Mantén presionada Ctrl (o Cmd) para seleccionar varios ganadores en caso de empate.</small>
        </div>

        {{-- Es ganador --}}
        <div class="mb-3">
            <label class="form-label">¿Es ganador?</label>
            <select name="es_ganador" id="es_ganador" class="form-control" required>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        {{-- Posición --}}
        <div class="mb-3">
            <label for="posicion" class="form-label">Posición</label>
            <input type="number" name="posicion" id="posicion" class="form-control" min="1" placeholder="Ej: 1">
            <small class="text-muted">En caso de empate, varios ganadores pueden compartir la misma posición.</small>
        </div>

        {{-- Monto ganado --}}
        <div class="mb-3">
            <label for="monto_ganado" class="form-label">Monto ganado</label>
            <input type="number" step="0.01" name="monto_ganado" id="monto_ganado"
                   class="form-control" placeholder="Se calculará automáticamente" readonly>
        </div>

        {{-- Porcentaje --}}
        <div class="mb-3">
            <label for="porcentaje" class="form-label">Porcentaje del premio (%)</label>
            <input type="number" step="0.01" name="porcentaje" id="porcentaje"
                   class="form-control" placeholder="Se calculará automáticamente" readonly>
        </div>

        <a href="{{ route('ganadores.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>

{{-- Script para calcular monto y porcentaje en tiempo real --}}
<script>
    document.getElementById('remate_id').addEventListener('change', calcularMonto);
    document.getElementById('es_ganador').addEventListener('change', calcularMonto);

    function calcularMonto() {
        let remateSelect = document.getElementById('remate_id');
        let selected = Array.from(remateSelect.selectedOptions).map(o => o.value);
        let esGanador = document.getElementById('es_ganador').value;

        if (selected.length > 0 && esGanador == 1) {
            fetch('{{ route("ganadores.calcularMonto") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ remate_ids: selected })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('monto_ganado').value = data.monto;
                document.getElementById('porcentaje').value = data.porcentaje;
            });
        } else {
            document.getElementById('monto_ganado').value = 0;
            document.getElementById('porcentaje').value = 0;
        }
    }
</script>
@endsection
