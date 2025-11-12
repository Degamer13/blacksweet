@extends(Auth::user()->hasRole('ventas') ? 'layouts.app1' : 'layouts.admin')
@section('content')
<style>
    /* Estilos personalizados para el diseño */
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

    tfoot tr th {
        font-weight: bold;
        background-color: #ddb892;
        font-size: 20px;
    }

    .alert {
        margin-top: 20px;
        font-size: 20px;
    }

    table tbody tr:nth-child(odd) { background-color: #f4f1de; }
    table tbody tr:nth-child(even) { background-color: #e9edc9; }

    table tbody td:nth-child(1) { background-color: #bc6c25; color: white; font-weight: bold; }
    table tbody td:nth-child(2) { background-color: #3d405b; color: white; font-weight: bold !important; }
    table tbody td:nth-child(3),
    table tbody td:nth-child(4),
    table tbody td:nth-child(5),
    table tbody td:nth-child(6) { background-color: #ffcb69; color: black; font-weight: bold !important; }
    table tbody td:nth-child(7) { background-color: #6a994e; color: black; font-weight: bold !important; }
    table tbody td:nth-child(8) { background-color: #8ecae6; color: black; font-weight: bold !important; }
    table tbody td:nth-child(9) { background-color: #ddb892; color: black; font-weight: bold !important; }
    table tbody td:nth-child(10) { background-color:#faf9f8; color: black; font-weight: bold !important; }

    .main-sidebar {
        position: fixed;
        z-index: 1050;
        height: 100vh;
    }

    .content-wrapper {
        margin-left: 250px;
        min-height: 100vh;
        overflow: auto;
    }

    #carreras_container {
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-dialog {
        max-width: 90%;
        margin: 1.75rem auto;
    }

</style>

<div class="container">
    <h3 class="mb-3 text-center">Edición de Remates</h3>

    @if($ejemplares->isEmpty())
        <div class="alert alert-warning text-center">
            No hay carreras registradas.
        </div>
    @else
        <ul class="nav nav-tabs" id="rematesTabs" role="tablist">
            @foreach ($ejemplares as $race_id => $grupoEjemplares)
                <li class="nav-item">
                    <a class="nav-link @if($loop->first) active @endif" id="race{{ $race_id }}-tab" data-bs-toggle="tab" href="#race{{ $race_id }}" role="tab">
                        Carrera {{ $race_id }}
                    </a>
                </li>
            @endforeach
        </ul>
        <form action="{{ route('remates.updateGlobal') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="tab-content mt-3" id="rematesTabsContent">
                @foreach ($ejemplares as $race_id => $grupoEjemplares)
                <div class="tab-pane fade @if($loop->first) show active @endif" id="race{{ $race_id }}" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                            <tbody id="race{{ $race_id }}_body">
                                @foreach ($remates[$race_id] ?? [] as $remate)
                                <tr>
                                    <!-- Campo hidden para race_id -->
                                    <input type="hidden" name="race_id[{{ $remate->id }}]" value="{{ $race_id }}">
                                    <!-- Campo hidden para ejemplar_name -->
                                    <input type="hidden" name="ejemplar_name[{{ $remate->id }}]" value="{{ $remate->ejemplar_name }}">

                                   <td><input type="number" name="number[{{ $remate->id }}]" value="{{ $remate->number }}" class="form-control monto" data-id="{{ $remate->id }}" readonly></td>
 <td><input type="text" name="ejemplar_name[{{ $remate->id }}]" value="{{ $remate->ejemplar_name }}" class="form-control monto" data-id="{{ $remate->id }}" readonly></td>
                                    <td><input type="number" name="monto1[{{ $remate->id }}]" value="{{ $remate->monto1 }}" class="form-control monto" data-id="{{ $remate->id }}"></td>
                                    <td><input type="number" name="monto2[{{ $remate->id }}]" value="{{ $remate->monto2 }}" class="form-control monto" data-id="{{ $remate->id }}"></td>
                                    <td><input type="number" name="monto3[{{ $remate->id }}]" value="{{ $remate->monto3 }}" class="form-control monto" data-id="{{ $remate->id }}"></td>
                                    <td><input type="number" name="monto4[{{ $remate->id }}]" value="{{ $remate->monto4 }}" class="form-control monto" data-id="{{ $remate->id }}"></td>
                                    <td><input type="text" name="cliente[{{ $remate->id }}]" value="{{ $remate->cliente }}" class="form-control"></td>
                                    <td><input type="number" name="total[{{ $remate->id }}]" value="{{ $remate->total }}" class="form-control total" data-id="{{ $remate->id }}" readonly></td>
                                    <td><input type="number" name="pote[{{ $remate->id }}]" value="{{ $remate->pote }}" class="form-control pote" data-id="{{ $remate->id }}"></td>
                                    <td><input type="number" name="acumulado[{{ $remate->id }}]" value="{{ $remate->acumulado }}" class="form-control acumulado" data-id="{{ $remate->id }}"></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2">Totales</th>
                                    <th id="totalMonto1_{{ $race_id }}">0</th>
                                    <th id="totalMonto2_{{ $race_id }}">0</th>
                                    <th id="totalMonto3_{{ $race_id }}">0</th>
                                    <th id="totalMonto4_{{ $race_id }}">0</th>
                                    <th colspan="4"></th>
                                </tr>
                                <tr>
                                    <th colspan="3">Total Subasta</th>
                                    <th colspan="3" id="totalSubasta_{{ $race_id }}">0</th>
                                    <th colspan="4"></th>
                                </tr>
                                <tr>
                                    <th colspan="3">Porcentaje (-30%)</th>
                                    <th colspan="3" id="porcentaje_{{ $race_id }}">0</th>
                                    <th colspan="4"></th>
                                </tr>
                                <tr>
                                    <th colspan="3">Pote</th>
                                    <th colspan="3" id="totalPote_{{ $race_id }}">0</th>
                                    <th colspan="4"></th>
                                </tr>
                                <tr>
                                    <th colspan="3">Acumulado</th>
                                    <th colspan="3" id="totalAcumulado_{{ $race_id }}">0</th>
                                    <th colspan="4"></th>
                                </tr>
                                <tr>
                                    <th colspan="3">Total a pagar</th>
                                    <th colspan="3" id="totalPagar_{{ $race_id }}">0</th>
                                    <th colspan="4"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </div>
        </form>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const updateTotals = (raceId) => {
        const inputsMonto1 = document.querySelectorAll(`#race${raceId}_body input[name^="monto1"]`);
        const inputsMonto2 = document.querySelectorAll(`#race${raceId}_body input[name^="monto2"]`);
        const inputsMonto3 = document.querySelectorAll(`#race${raceId}_body input[name^="monto3"]`);
        const inputsMonto4 = document.querySelectorAll(`#race${raceId}_body input[name^="monto4"]`);
        const inputsTotal = document.querySelectorAll(`#race${raceId}_body input[name^="total"]`);
        const inputsPote = document.querySelectorAll(`#race${raceId}_body input[name^="pote"]`);
        const inputsAcumulado = document.querySelectorAll(`#race${raceId}_body input[name^="acumulado"]`);

        function calculateTotal() {
            let totalMonto1 = 0, totalMonto2 = 0, totalMonto3 = 0, totalMonto4 = 0, totalSubasta = 0;

            const firstPote = parseFloat(inputsPote[0]?.value || 0);
            const firstAcumulado = parseFloat(inputsAcumulado[0]?.value || 0);

            inputsPote.forEach((input) => input.value = firstPote);
            inputsAcumulado.forEach((input) => input.value = firstAcumulado);

            inputsMonto1.forEach((input, index) => {
                let monto1 = parseFloat(input.value) || 0;
                let monto2 = parseFloat(inputsMonto2[index].value) || monto1 / 2;
                let monto3 = parseFloat(inputsMonto3[index].value) || monto2 / 2;
                let monto4 = parseFloat(inputsMonto4[index].value) || monto3 / 2;

                if (!inputsMonto2[index].value) inputsMonto2[index].value = monto2;
                if (!inputsMonto3[index].value) inputsMonto3[index].value = monto3;
                if (!inputsMonto4[index].value) inputsMonto4[index].value = monto4;

                const total = monto1 + monto2 + monto3 + monto4;
                inputsTotal[index].value = total;

                totalMonto1 += monto1;
                totalMonto2 += monto2;
                totalMonto3 += monto3;
                totalMonto4 += monto4;
                totalSubasta += total;
            });

            document.getElementById(`totalMonto1_${raceId}`).textContent = totalMonto1;
            document.getElementById(`totalMonto2_${raceId}`).textContent = totalMonto2;
            document.getElementById(`totalMonto3_${raceId}`).textContent = totalMonto3;
            document.getElementById(`totalMonto4_${raceId}`).textContent = totalMonto4;
            document.getElementById(`totalSubasta_${raceId}`).textContent = totalSubasta;
            document.getElementById(`porcentaje_${raceId}`).textContent = totalSubasta * 0.3;
            document.getElementById(`totalPote_${raceId}`).textContent = firstPote;
            document.getElementById(`totalAcumulado_${raceId}`).textContent = firstAcumulado;
            document.getElementById(`totalPagar_${raceId}`).textContent = totalSubasta - (totalSubasta * 0.3) + firstPote + firstAcumulado;
        }

        // Enganchar eventos
        [...inputsMonto1, ...inputsMonto2, ...inputsMonto3, ...inputsMonto4, ...inputsPote, ...inputsAcumulado]
            .forEach(input => input.addEventListener('input', calculateTotal));

        // 🔹 Ejecutar la primera vez para que no aparezca en cero
        calculateTotal();
    };

    @foreach ($ejemplares as $race_id => $grupoEjemplares)
        updateTotals({{ $race_id }});
    @endforeach
});
</script>
@endsection
