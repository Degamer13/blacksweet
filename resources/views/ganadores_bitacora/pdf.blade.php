<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Ganadores</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background: #3d405b; color: #fff; }
        tr:nth-child(odd) { background: #f4f1de; }
        tr:nth-child(even) { background: #e9edc9; }
    </style>
</head>
<body>
    <h3>Bitácora de Ganadores</h3>

    <table>
        <thead>
            <tr>
                <th>Carrera</th>
                <th>Ejemplar</th>
                <th>Cliente</th>
                <th>Ganador</th>
                <th>Posición</th>
                <th>Monto Ganado</th>
                <th>Porcentaje</th>
                <th>Total del Remate</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bitacoras as $bitacora)
                <tr>
                    <td>{{ $bitacora->remate->race_id }}</td>
                    <td>{{ $bitacora->remate->ejemplar_name }}</td>
                    <td>{{ $bitacora->remate->cliente }}</td>
                    <td>{{ $bitacora->es_ganador ? 'Sí' : 'No' }}</td>
                    <td>{{ $bitacora->posicion ?? '-' }}</td>
                    <td>{{ number_format($bitacora->monto_ganado, 2) }}</td>
                    <td>{{ number_format($bitacora->porcentaje, 2) }}%</td>
                    <td>{{ number_format($bitacora->total_pagar, 2) }}</td>
                    <td>{{ $bitacora->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
