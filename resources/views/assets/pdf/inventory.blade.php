<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Inventario de Activos</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        h1 {
            margin: 0;
            color: #333;
        }

        .date {
            float: right;
            color: #666;
            font-size: 9pt;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            bg-color: #f2f2f2;
            font-weight: bold;
            font-size: 9pt;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
            display: inline-block;
        }

        .status-available {
            background-color: #10b981;
            color: white;
        }

        /* Green */
        .status-assigned {
            background-color: #3b82f6;
            color: white;
        }

        /* Blue */
        .status-maintenance {
            background-color: #f59e0b;
            color: white;
        }

        /* Orange */
        .status-written_off {
            background-color: #ef4444;
            color: white;
        }

        /* Red */

        .meta-info {
            font-size: 8pt;
            color: #555;
        }
    </style>
</head>

<body>

    <div class="header">
        <span class="date">Generado: {{ $generatedDate }}</span>
        <h1>Inventario de Activos</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%">Código</th>
                <th style="width: 20%">Nombre / Marca / Modelo</th>
                <th style="width: 12%">Categoría</th>
                <th style="width: 12%">Estado</th>
                <th style="width: 15%">Ubicación</th>
                <th style="width: 10%">Valor Ref.</th>
                <th style="width: 21%">Detalle / Asignación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $asset)
                <tr>
                    <td>
                        <strong>{{ $asset->codigo_interno }}</strong><br>
                        <span class="meta-info">{{ $asset->codigo_barra }}</span>
                    </td>
                    <td>
                        <strong>{{ $asset->nombre }}</strong><br>
                        <span class="meta-info">
                            {{ $asset->marca }} {{ $asset->modelo }}
                            @if($asset->numero_serie)
                                <br>SN: {{ $asset->numero_serie }}
                            @endif
                        </span>
                    </td>
                    <td>{{ $asset->category->nombre ?? 'N/A' }}</td>
                    <td>
                        @php
                            $statusMap = [
                                'available' => ['label' => 'Disponible', 'class' => 'status-available'],
                                'assigned' => ['label' => 'Asignado', 'class' => 'status-assigned'],
                                'maintenance' => ['label' => 'En Mantención', 'class' => 'status-maintenance'],
                                'written_off' => ['label' => 'Dado de Baja', 'class' => 'status-written_off'],
                            ];
                            $status = $statusMap[$asset->estado] ?? ['label' => $asset->estado, 'class' => ''];
                        @endphp
                        <span class="status-badge {{ $status['class'] }}">
                            {{ $status['label'] }}
                        </span>
                    </td>
                    <td>
                        {{ $asset->ubicacion ?? 'No definida' }}
                        @if($asset->fecha_adquisicion)
                            <br><span class="meta-info">Adq:
                                {{ \Carbon\Carbon::parse($asset->fecha_adquisicion)->format('d/m/Y') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($asset->valor_referencial)
                            ${{ number_format($asset->valor_referencial, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($asset->estado === 'assigned' && $asset->activeAssignment)
                            <strong>Asignado a:</strong><br>
                            {{ $asset->activeAssignment->assigned_to_name }}
                            <br>
                            <span class="meta-info">Desde:
                                {{ \Carbon\Carbon::parse($asset->activeAssignment->fecha_entrega)->format('d/m/Y') }}</span>
                        @elseif($asset->estado === 'maintenance')
                            <strong>En taller</strong>
                        @elseif($asset->estado === 'written_off' && $asset->writeOff)
                            <strong>Motivo Baja:</strong><br>
                            {{ Str::limit($asset->writeOff->motivo, 50) }}
                            <br>
                            <span class="meta-info">Fecha:
                                {{ \Carbon\Carbon::parse($asset->writeOff->fecha)->format('d/m/Y') }}</span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        No se encontraron activos con los filtros seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>