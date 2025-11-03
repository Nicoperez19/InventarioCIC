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
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .info-section {
            margin-bottom: 20px;
            padding: 15px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            font-size: 11px;
        }

        thead {
            background-color: #1e40af;
            color: white;
        }

        th {
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1e3a8a;
            font-size: 11px;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Solicitud de Insumos</h1>
    </div>

    <div class="info-section">
        <div class="info-item">
            <span class="info-label">N° Solicitud:</span>
            <span class="info-value">{{ $solicitud->numero_solicitud }}</span>
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
            <span class="info-label">Departamento:</span>
            <span class="info-value">{{ $solicitud->departamento->nombre_depto ?? 'N/A' }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 100%;">Insumo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($solicitud->items as $item)
                <tr>
                    <td>{{ $item->insumo->nombre_insumo ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generado el {{ $fecha }} | GestionCIC</p>
    </div>
</body>

</html>
