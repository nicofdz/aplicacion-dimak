<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Historial de Asignaciones</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        h1 {
            font-size: 14pt;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 11pt;
            color: #555;
            margin-bottom: 15px;
            font-weight: normal;
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
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .status-badge {
            font-weight: bold;
            font-size: 9pt;
        }

        .text-green {
            color: #15803d;
        }

        .text-yellow {
            color: #a16207;
        }

        .text-orange {
            color: #c2410c;
        }

        .text-red {
            color: #b91c1c;
        }

        .text-gray {
            color: #374151;
        }

        .meta-info {
            font-size: 9pt;
            color: #666;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h1>Historial de Asignaciones: {{ $asset->nombre }}</h1>
    <div class="meta-info">
        <strong>Código Interno:</strong> {{ $asset->codigo_interno }} <br>
        <strong>Generado el:</strong> {{ now()->format('d/m/Y H:i') }} <br>
        @if(request('start_date') || request('end_date'))
            <strong>Filtro:</strong>
            {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Inicio' }}
            -
            {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Fin' }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Asignado A</th>
                <th>Fecha Entrega</th>
                <th>Fecha Devolución</th>
                <th>Estado Devolución</th>
                <th>Comentarios / Incidentes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assignments as $assignment)
                <tr>
                    <td>
                        @if($assignment->user)
                            {{ $assignment->user->name }} <br><span
                                style="color:#666; font-size:8pt;">{{ $assignment->user->rut }}</span>
                        @elseif($assignment->worker)
                            {{ $assignment->worker->nombre }} <br><span
                                style="color:#666; font-size:8pt;">{{ $assignment->worker->rut }}</span>
                        @else
                            {{ $assignment->trabajador_nombre ?? 'N/A' }} <br><span
                                style="color:#666; font-size:8pt;">{{ $assignment->trabajador_rut }}</span>
                        @endif
                    </td>
                    <td>
                        {{ $assignment->fecha_entrega ? $assignment->fecha_entrega->format('d/m/Y') : '-' }}
                    </td>
                    <td>
                        {{ $assignment->fecha_devolucion ? $assignment->fecha_devolucion->format('d/m/Y H:i') : 'En curso' }}
                    </td>
                    <td>
                        @if($assignment->fecha_devolucion)
                                        <span class="status-badge 
                                                                        @if($assignment->estado_devolucion == 'good') text-green
                                                                        @elseif($assignment->estado_devolucion == 'regular') text-yellow
                                                                        @elseif($assignment->estado_devolucion == 'bad') text-orange
                                                                        @elseif($assignment->estado_devolucion == 'damaged') text-red
                                                                        @else text-gray @endif">
                                            {{ match ($assignment->estado_devolucion) {
                                'good' => 'Bueno',
                                'regular' => 'Regular',
                                'bad' => 'Malo',
                                'damaged' => 'Dañado',
                                default => $assignment->estado_devolucion ?? ''
                            } }}
                                        </span>
                        @else
                            <span style="color:blue;">Activo</span>
                        @endif
                    </td>
                    <td>
                        @if($assignment->comentarios_devolucion)
                            <div><strong>Dev:</strong> {{ $assignment->comentarios_devolucion }}</div>
                        @endif
                        @if($assignment->observaciones)
                            <div style="margin-top:4px; font-size:9pt; color:#555;"><strong>Obs:</strong>
                                {{ $assignment->observaciones }}</div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No hay registros para este periodo.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>