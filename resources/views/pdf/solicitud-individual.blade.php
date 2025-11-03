<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud {{ $solicitud->numero_solicitud }} - GestionCIC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 20px;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .header .subtitle {
            font-size: 14px;
            color: #666;
            font-weight: bold;
        }

        .info-section {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #1e40af;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            min-width: 120px;
        }

        .info-value {
            color: #333;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-aprobada {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-rechazada {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-entregada {
            background-color: #dbeafe;
            color: #1e40af;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            font-size: 10px;
        }

        thead {
            background-color: #1e40af;
            color: white;
        }

        th {
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1e3a8a;
            font-size: 10px;
        }

        td {
            padding: 6px;
            border: 1px solid #ddd;
            font-size: 9px;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #eff6ff;
            border-radius: 5px;
            border-left: 4px solid #3b82f6;
        }

        .summary-title {
            font-weight: bold;
            font-size: 12px;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
        }

        .observaciones {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff9e6;
            border-radius: 5px;
            border-left: 4px solid #f59e0b;
        }

        .observaciones-title {
            font-weight: bold;
            font-size: 11px;
            color: #92400e;
            margin-bottom: 8px;
        }

        .observaciones-text {
            font-size: 10px;
            color: #333;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Gestión de Solicitudes - GestionCIC</h1>
        <div class="subtitle">Solicitud de Insumos</div>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">N° Solicitud:</span>
                <span class="info-value" style="font-weight: bold; font-size: 13px;">{{ $solicitud->numero_solicitud }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Fecha:</span>
                <span class="info-value">{{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Solicitante:</span>
                <span class="info-value">{{ $solicitud->user->nombre ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">RUN:</span>
                <span class="info-value">{{ $solicitud->user->run ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Departamento:</span>
                <span class="info-value">{{ $solicitud->departamento->nombre_depto ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Estado:</span>
                <span class="info-value">
                    @php
                        $estadoClass = 'badge-' . $solicitud->estado;
                    @endphp
                    <span class="badge {{ $estadoClass }}">{{ ucfirst($solicitud->estado) }}</span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Tipo:</span>
                <span class="info-value">{{ ucfirst($solicitud->tipo_solicitud) }}</span>
            </div>
            @if($solicitud->tipoInsumo)
            <div class="info-item">
                <span class="info-label">Tipo de Insumo:</span>
                <span class="info-value">{{ $solicitud->tipoInsumo->nombre_tipo }}</span>
            </div>
            @endif
            @if($solicitud->fecha_aprobacion)
            <div class="info-item">
                <span class="info-label">Fecha Aprobación:</span>
                <span class="info-value">{{ $solicitud->fecha_aprobacion->format('d/m/Y H:i') }}</span>
            </div>
            @endif
            @if($solicitud->aprobadoPor)
            <div class="info-item">
                <span class="info-label">Aprobado por:</span>
                <span class="info-value">{{ $solicitud->aprobadoPor->nombre }}</span>
            </div>
            @endif
            @if($solicitud->fecha_entrega)
            <div class="info-item">
                <span class="info-label">Fecha Entrega:</span>
                <span class="info-value">{{ $solicitud->fecha_entrega->format('d/m/Y H:i') }}</span>
            </div>
            @endif
            @if($solicitud->entregadoPor)
            <div class="info-item">
                <span class="info-label">Entregado por:</span>
                <span class="info-value">{{ $solicitud->entregadoPor->nombre }}</span>
            </div>
            @endif
        </div>
    </div>

    @if($solicitud->observaciones)
    <div class="observaciones">
        <div class="observaciones-title">Observaciones:</div>
        <div class="observaciones-text">{{ $solicitud->observaciones }}</div>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 30%;">Insumo</th>
                <th style="width: 12%;" class="text-center">ID Insumo</th>
                <th style="width: 12%;" class="text-center">Unidad</th>
                <th style="width: 12%;" class="text-center">Cant. Solicitada</th>
                <th style="width: 12%;" class="text-center">Cant. Aprobada</th>
                <th style="width: 12%;" class="text-center">Cant. Entregada</th>
                <th style="width: 10%;" class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($solicitud->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->insumo->nombre_insumo ?? 'N/A' }}</td>
                    <td class="text-center">{{ $item->insumo->id_insumo ?? 'N/A' }}</td>
                    <td class="text-center">{{ $item->insumo->unidadMedida->nombre_unidad_medida ?? 'N/A' }}</td>
                    <td class="text-center">{{ $item->cantidad_solicitada }}</td>
                    <td class="text-center">{{ $item->cantidad_aprobada ?? '-' }}</td>
                    <td class="text-center">{{ $item->cantidad_entregada ?? '-' }}</td>
                    <td class="text-center">
                        @php
                            $estadoItemClass = 'badge-' . ($item->estado_item ?? 'pendiente');
                        @endphp
                        <span class="badge {{ $estadoItemClass }}">{{ ucfirst($item->estado_item ?? 'pendiente') }}</span>
                    </td>
                </tr>
                @if($item->observaciones_item)
                <tr>
                    <td colspan="8" style="padding-left: 30px; font-size: 8px; color: #666; font-style: italic;">
                        Observación: {{ $item->observaciones_item }}
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #e7e6e6; font-weight: bold;">
                <td colspan="4" class="text-right" style="padding-right: 15px;">TOTALES:</td>
                <td class="text-center">{{ $solicitud->items->sum('cantidad_solicitada') }}</td>
                <td class="text-center">{{ $solicitud->items->sum('cantidad_aprobada') ?? 0 }}</td>
                <td class="text-center">{{ $solicitud->items->sum('cantidad_entregada') ?? 0 }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <div class="summary-title">Resumen de la Solicitud</div>
        <div class="summary-grid">
            <div class="summary-item">
                <span>Total de Items:</span>
                <span style="font-weight: bold;">{{ $solicitud->items->count() }}</span>
            </div>
            <div class="summary-item">
                <span>Total Solicitado:</span>
                <span style="font-weight: bold;">{{ $solicitud->items->sum('cantidad_solicitada') }} unidades</span>
            </div>
            <div class="summary-item">
                <span>Total Aprobado:</span>
                <span style="font-weight: bold;">{{ $solicitud->items->sum('cantidad_aprobada') ?? 0 }} unidades</span>
            </div>
            <div class="summary-item">
                <span>Total Entregado:</span>
                <span style="font-weight: bold;">{{ $solicitud->items->sum('cantidad_entregada') ?? 0 }} unidades</span>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Generado el {{ $fecha }} | GestionCIC - Sistema de Gestión de Insumos</p>
    </div>
</body>

</html>

