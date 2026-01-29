<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Historial de Mantenciones: {{ $asset->nombre }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-preventiva {
            background-color: #eff6ff;
            color: #1e40af;
        }

        .badge-correctiva {
            background-color: #fef2f2;
            color: #991b1b;
        }

        .status-ongoing {
            color: #d97706;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Historial de Mantenciones</h1>
        <p>{{ $asset->nombre }} ({{ $asset->codigo_interno }})</p>
        <p>Fecha de emisión: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Fecha Inicio</th>
                <th>Fecha Término</th>
                <th>Descripción / Motivo</th>
                <th>Solución / Resultado</th>
                <th>Costo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($maintenances as $maintenance)
                <tr>
                    <td>
                        <span
                            class="badge {{ $maintenance->tipo === 'preventiva' ? 'badge-preventiva' : 'badge-correctiva' }}">
                            {{ ucfirst($maintenance->tipo) }}
                        </span>
                    </td>
                    <td>{{ $maintenance->fecha ? $maintenance->fecha->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($maintenance->fecha_termino)
                            {{ $maintenance->fecha_termino->format('d/m/Y') }}
                        @else
                            <span class="status-ongoing">En Proceso</span>
                        @endif
                    </td>
                    <td>{{ $maintenance->descripcion }}</td>
                    <td>
                        @if($maintenance->detalles_solucion)
                            {{ $maintenance->detalles_solucion }}
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($maintenance->costo)
                            ${{ number_format($maintenance->costo, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No hay registros de mantención para este activo.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>