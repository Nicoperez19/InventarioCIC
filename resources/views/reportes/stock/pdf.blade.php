<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo ?? 'Reporte de Stock Crítico' }} - GestionCIC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.5;
            padding: 20px 50px;
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            margin-bottom: 35px;
            padding-bottom: 15px;
            border-bottom: 1px solid #d1d5db;
        }
        .header h1 {
            font-size: 20px;
            color: #111827;
            margin-bottom: 5px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        .header .subtitle {
            font-size: 13px;
            color: #6b7280;
            font-weight: 400;
        }
        .info-section {
            margin-bottom: 30px;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: 500;
            color: #4b5563;
        }
        .info-value {
            color: #111827;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 14px;
            color: #111827;
            margin-bottom: 18px;
            padding-bottom: 8px;
            border-bottom: 1px solid #d1d5db;
            font-weight: 600;
        }
        .content-wrapper {
            page-break-inside: avoid;
        }
        .report-title {
            font-size: 18px;
            color: #111827;
            margin-bottom: 8px;
            text-align: center;
            font-weight: 600;
            padding-bottom: 0;
            border-bottom: none;
        }
        .table-container {
            margin: 0 auto;
            max-width: 95%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
        }
        table thead {
            background-color: #f9fafb;
        }
        table th {
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #374151;
            border: 1px solid #d1d5db;
        }
        table td {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            color: #1f2937;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 50px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
        }
        .no-data {
            text-align: center;
            padding: 30px 20px;
            color: #6b7280;
            font-style: italic;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            background-color: #f9fafb;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="report-title">
            {{ $titulo ?? 'Reporte de Stock Crítico' }}
        </div>
        
        <!-- Insumos según la pestaña activa -->
        @if(count($insumos ?? []) > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Insumo</th>
                            <th>Tipo</th>
                            <th class="text-right">Stock Actual</th>
                            <th>Unidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($insumos as $insumo)
                            <tr>
                                <td>{{ $insumo->nombre_insumo }}</td>
                                <td>{{ $insumo->tipoInsumo->nombre_tipo ?? 'N/A' }}</td>
                                <td class="text-right">{{ number_format($insumo->stock_actual, 0, ',', '.') }}</td>
                                <td>{{ $insumo->unidadMedida->nombre_unidad_medida ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-data">
                @if(($tab ?? 'agotados') === 'agotados')
                    No hay insumos agotados
                @else
                    No hay insumos con stock bajo
                @endif
            </div>
        @endif
    </div>

    <div class="footer">
        <p>Reporte generado el {{ $fecha }} por el sistema GestionCIC</p>
    </div>
</body>
</html>

