<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Códigos QR de Usuarios - InventarioCIC</title>
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
            border-bottom: 2px solid #306073;
        }
        .header h1 {
            font-size: 20px;
            color: #306073;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
        }
        table thead {
            background-color: #306073;
            color: white;
        }
        table th {
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #264d5c;
        }
        table td {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            color: #1f2937;
            vertical-align: middle;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-center {
            text-align: center;
        }
        .qr-code-cell {
            text-align: center;
            width: 100px;
        }
        .qr-code-image {
            max-width: 80px;
            max-height: 80px;
            width: auto;
            height: auto;
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
    <div class="header">
        <h1>Gestión de Insumos - InventarioCIC</h1>
        <div class="subtitle">Códigos QR de Usuarios</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Fecha de Generación:</span>
            <span class="info-value">{{ $fecha }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total de Usuarios:</span>
            <span class="info-value">{{ $users->count() }}</span>
        </div>
    </div>

    @if($users->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Usuario</th>
                    <th style="width: 15%;">RUN</th>
                    <th style="width: 25%;">Correo</th>
                    <th style="width: 20%;">Departamento</th>
                    <th class="text-center" style="width: 25%;">Código QR</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->nombre }}</td>
                        <td>{{ $user->run }}</td>
                        <td>{{ $user->correo }}</td>
                        <td>{{ $user->departamento->nombre_depto ?? 'Sin departamento' }}</td>
                        <td class="qr-code-cell">
                            @if(isset($user->qr_image_base64))
                                <img src="{{ $user->qr_image_base64 }}" alt="Código QR" class="qr-code-image" />
                            @else
                                <span style="color: #9ca3af;">No disponible</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>No hay usuarios con códigos QR generados.</p>
        </div>
    @endif

    <div class="footer">
        <p>Documento generado el {{ $fecha }} - InventarioCIC</p>
    </div>
</body>
</html>

