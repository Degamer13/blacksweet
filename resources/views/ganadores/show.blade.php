@extends(Auth::user()->hasRole('ventas') || Auth::user()->hasRole('usuarios') ? 'layouts.app1' : 'layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-lg-9">
            <h3>Detalle del Ganador</h3>
        </div>
        <div class="col-lg-3 text-right">
            <a class="btn btn-secondary" href="{{ route('ganadores.index') }}">
                <!-- Flecha SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                     viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H2.707l4.147 4.146a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 0 1 .708.708L2.707 7.5H14.5A.5.5 0 0 1 15 8z"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <div class="card shadow rounded-3">
        <div class="card-body">
            <h5 class="card-title mb-3">Información del Ganador</h5>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Carrera:</strong></div>
                <div class="col-md-8">{{ $ganador->remate->race->name ?? 'N/A' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Ejemplar:</strong></div>
                <div class="col-md-8">{{ $ganador->remate->ejemplar_name }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Cliente:</strong></div>
                <div class="col-md-8">{{ $ganador->remate->cliente }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4"><strong>¿Ganador?</strong></div>
                <div class="col-md-8">
                    @if($ganador->es_ganador)
                        <span class="badge bg-success">✅ Sí</span>
                    @else
                        <span class="badge bg-danger">❌ No</span>
                    @endif
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Posición:</strong></div>
                <div class="col-md-8">{{ $ganador->posicion ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Monto Ganado:</strong></div>
                <div class="col-md-8">
                    @if($ganador->es_ganador)
                        {{ number_format($ganador->monto_ganado, 2) }}
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Monto sin Empate:</strong></div>
                <div class="col-md-8">
                    {{ number_format($ganador->remate->total_pagar ?? 0, 2) }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Porcentaje:</strong></div>
                <div class="col-md-8">
                    @if($ganador->es_ganador)
                        {{ number_format($ganador->porcentaje, 2) }} %
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Total Apostado:</strong></div>
                <div class="col-md-8">{{ number_format($ganador->remate->total ?? 0, 2) }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
