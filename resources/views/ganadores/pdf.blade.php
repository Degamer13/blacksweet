<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket Ganador {{ str_pad($ganador->id,3,'0',STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            width: 80mm;
            margin: 0 auto;
            padding: 5px;
        }

        .ticket {
            border: 1px dashed #000;
            padding: 10px;
            text-align: center;
        }

        h2, h3 {
            margin: 5px 0;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            color: #fff;
        }
        .bg-success { background-color: #28a745; }
        .bg-danger { background-color: #dc3545; }

        table {
            width: 100%;
            margin-top: 10px;
            font-size: 12px;
        }

        td {
            padding: 3px 0;
        }

        .total {
            font-weight: bold;
            font-size: 14px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .footer {
            font-size: 10px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <h2>🏆 Black Sweet Remate</h2>
        <h3>Ganador #{{ str_pad($ganador->id,3,'0',STR_PAD_LEFT) }}</h3>
        <div class="line"></div>

        <table>
            <tr>
                <td><strong>Código:</strong></td>
                <td>{{ str_pad($ganador->id,3,'0',STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td><strong>Fecha/Hora:</strong></td>
                <td>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Carrera:</strong></td>
                <td>{{ $ganador->remate->race->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Ejemplar:</strong></td>
                <td>{{ $ganador->remate->ejemplar_name }}</td>
            </tr>
            <tr>
                <td><strong>Cliente:</strong></td>
                <td>{{ $ganador->remate->cliente }}</td>
            </tr>
            <tr>
                <td><strong>Ganador:</strong></td>
                <td>
                    @if($ganador->es_ganador)
                        <span class="badge bg-success">Sí</span>
                    @else
                        <span class="badge bg-danger">No</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Posición:</strong></td>
                <td>{{ $ganador->posicion ?? '-' }}</td>
            </tr>

                <td><strong>Total Apostado:</strong><td>
                {{ number_format($ganador->remate->total ?? 0, 2) }}

            <tr>
                <td><strong>Total Ganado:</strong></td>
                <td class="total">
                    {{ number_format($ganador->monto_ganado, 2) }}Bs
                </td>
            </tr>
        </table>

        <div class="line"></div>
        <p class="footer">Gracias por participar en Black Sweet Remate!</p>
    </div>
</body>
</html>
