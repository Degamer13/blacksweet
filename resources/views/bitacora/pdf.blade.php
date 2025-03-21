<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora - PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2, h3, h4 {
            text-align: center;
            margin-bottom: 10px;
        }
        .fecha {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-size: 12px;
        }
        .total-row {
            font-weight: bold;
            background-color: #ddd;
        }
        .race-title {
            background-color: #6a994e;
            color: white;
            font-weight: bold;
            padding: 8px;
            margin-top: 15px;
        }
        .date-title {
            background-color: #1d3557;
            color: white;
            font-weight: bold;
            padding: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Registros de Bitácoras</h2>
    <p class="fecha">Fecha de Generación: {{ now()->format('d/m/Y H:i') }}</p>

    @foreach ($bitacoras as $race_id => $grupoBitacoras)
        <h3 class="race-title">Carrera {{ $race_id }}</h3>

        @foreach ($grupoBitacoras->groupBy(function($bitacora) { return \Carbon\Carbon::parse($bitacora->created_at)->format('d/m/Y'); }) as $fecha => $bitacorasPorFecha)
            <h4 class="date-title">Fecha: {{ $fecha }}</h4>

            <table>
                <thead>
                    <tr>
                        <th>Nro</th>
                        <th>Ejemplar</th>
                        <th>Cliente</th>
                        <th>Monto1</th>
                        <th>Monto2</th>
                        <th>Monto3</th>
                        <th>Monto4</th>
                        <th>Total</th>
                        <th>Pote</th>
                        <th>Acumulado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bitacorasPorFecha as $bitacora)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $bitacora->ejemplar_name ?? 'N/A' }}</td>
                        <td>{{ $bitacora->cliente ?? 'N/A' }}</td>
                        <td>{{ number_format($bitacora->monto1 ?? 0, 2) }}</td>
                        <td>{{ number_format($bitacora->monto2 ?? 0, 2) }}</td>
                        <td>{{ number_format($bitacora->monto3 ?? 0, 2) }}</td>
                        <td>{{ number_format($bitacora->monto4 ?? 0, 2) }}</td>
                        <td>{{ number_format($bitacora->total ?? 0, 2) }}</td>
                        <td>{{ number_format($bitacora->pote ?? 0, 2) }}</td>
                        <td>{{ number_format($bitacora->acumulado ?? 0, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($bitacora->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @php
                        $totalMonto1 = $bitacorasPorFecha->sum('monto1');
                        $totalMonto2 = $bitacorasPorFecha->sum('monto2');
                        $totalMonto3 = $bitacorasPorFecha->sum('monto3');
                        $totalMonto4 = $bitacorasPorFecha->sum('monto4');
                        $totalSubasta = $bitacorasPorFecha->sum('total');
                        $porcentaje = $totalSubasta * 0.3;
                        $pote = $bitacorasPorFecha->first()->pote ?? 0;
                        $acumulado = $bitacorasPorFecha->first()->acumulado ?? 0;
                        $totalPagar = $bitacorasPorFecha->first()->total_pagar ?? 0;
                    @endphp
                    <tr class="total-row">
                        <td colspan="3">Totales</td>
                        <td>{{ number_format($totalMonto1, 2) }}</td>
                        <td>{{ number_format($totalMonto2, 2) }}</td>
                        <td>{{ number_format($totalMonto3, 2) }}</td>
                        <td>{{ number_format($totalMonto4, 2) }}</td>
                        <td>{{ number_format($totalSubasta, 2) }}</td>
                        <td colspan="3"></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3">Porcentaje (-30%)</td>
                        <td colspan="3">{{ number_format($porcentaje, 2) }}</td>
                        <td colspan="5"></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3">Pote</td>
                        <td colspan="3">{{ number_format($pote, 2) }}</td>
                        <td colspan="5"></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3">Acumulado</td>
                        <td colspan="3">{{ number_format($acumulado, 2) }}</td>
                        <td colspan="5"></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3">Total a pagar</td>
                        <td colspan="3">{{ number_format($totalPagar, 2) }}</td>
                        <td colspan="5"></td>
                    </tr>
                </tfoot>
            </table>
        @endforeach
    @endforeach
</body>
</html>
