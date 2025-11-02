<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insumos por Tipo - {{ $tipoInsumo->nombre_tipo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
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
        }

        .info-section {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #1e40af;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }

        thead {
            background-color: #1e40af;
            color: white;
        }

        th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1e3a8a;
        }

        td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:hover {
            background-color: #e9ecef;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 10px;
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
            font-size: 14px;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Gestión de Insumos - GestionCIC</h1>
        <div class="subtitle">Reporte de Insumos por Tipo</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Tipo de Insumo:</span>
            <span class="info-value" style="color: {{ $tipoInsumo->color ?? '#333' }}; font-weight: bold;">
                {{ $tipoInsumo->nombre_tipo }}
            </span>
        </div>
        @if($tipoInsumo->descripcion)
            <div class="info-row">
                <span class="info-label">Descripción:</span>
                <span class="info-value">{{ $tipoInsumo->descripcion }}</span>
            </div>
        @endif
        <div class="info-row">
            <span class="info-label">Fecha de Generación:</span>
            <span class="info-value">{{ $fecha }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total de Insumos:</span>
            <span class="info-value" style="font-weight: bold; font-size: 14px;">{{ $insumos->count() }}</span>
        </div>
    </div>

    @if($insumos->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">ID Insumo</th>
                    <th style="width: 35%;">Nombre</th>
                    <th style="width: 15%;" class="text-center">Stock Actual</th>
                    <th style="width: 15%;" class="text-center">Unidad</th>
                    <th style="width: 20%;">Código de Barras</th>
                </tr>
            </thead>
            <tbody>
                @foreach($insumos as $insumo)
                    <tr>
                        <td>{{ $insumo->id_insumo }}</td>
                        <td>{{ $insumo->nombre_insumo }}</td>
                        <td class="text-center">
                            @if($insumo->stock_actual > 0)
                                <span class="badge badge-success">{{ $insumo->stock_actual }}</span>
                            @else
                                <span class="badge badge-danger">{{ $insumo->stock_actual }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $insumo->unidadMedida ? $insumo->unidadMedida->nombre_unidad_medida : ($insumo->id_unidad ?? 'N/A') }}
                        </td>
                        <td>{{ $insumo->codigo_barra ?? 'Sin código' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-title">Resumen de Stock</div>
            <div class="summary-row">
                <span>Total de Insumos:</span>
                <span style="font-weight: bold;">{{ $insumos->count() }}</span>
            </div>
            <div class="summary-row">
                <span>Stock Total:</span>
                <span style="font-weight: bold;">{{ $insumos->sum('stock_actual') }} unidades</span>
            </div>
            <div class="summary-row">
                <span>Insumos con Stock Disponible:</span>
                <span style="font-weight: bold;">{{ $insumos->where('stock_actual', '>', 0)->count() }}</span>
            </div>
            <div class="summary-row">
                <span>Insumos sin Stock:</span>
                <span style="font-weight: bold;">{{ $insumos->where('stock_actual', '<=', 0)->count() }}</span>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 40px; background-color: #f8f9fa; border-radius: 5px; margin-top: 20px;">
            <p style="font-size: 14px; color: #666;">No hay insumos asociados a este tipo de insumo.</p>
        </div>
    @endif


</body>

</html>