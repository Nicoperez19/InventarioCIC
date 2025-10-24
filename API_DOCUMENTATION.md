# API Documentation - Sistema de Gestión CIC

## Autenticación

### Login
```
POST /api/login
Content-Type: application/json

{
    "correo": "usuario@ejemplo.com",
    "contrasena": "password123"
}
```

**Respuesta:**
```json
{
    "success": true,
    "data": {
        "user": {
            "run": "12345678-9",
            "nombre": "Usuario Ejemplo",
            "correo": "usuario@ejemplo.com",
            "departamento": {...},
            "permissions": [...]
        },
        "token": "1|abc123..."
    },
    "message": "Login exitoso"
}
```

### Logout
```
POST /api/logout
Authorization: Bearer {token}
```

## Endpoints Principales

### Usuarios
- `GET /api/users` - Listar usuarios
- `GET /api/users/{user}` - Obtener usuario
- `POST /api/users` - Crear usuario
- `PUT /api/users/{user}` - Actualizar usuario
- `DELETE /api/users/{user}` - Eliminar usuario
- `GET /api/users/departamentos` - Obtener departamentos
- `GET /api/users/permissions` - Obtener permisos

### Departamentos
- `GET /api/departamentos` - Listar departamentos
- `GET /api/departamentos/{departamento}` - Obtener departamento
- `POST /api/departamentos` - Crear departamento
- `PUT /api/departamentos/{departamento}` - Actualizar departamento
- `DELETE /api/departamentos/{departamento}` - Eliminar departamento

### Unidades de Medida
- `GET /api/unidades-medida` - Listar unidades
- `GET /api/unidades-medida/{unidad}` - Obtener unidad
- `POST /api/unidades-medida` - Crear unidad
- `PUT /api/unidades-medida/{unidad}` - Actualizar unidad
- `DELETE /api/unidades-medida/{unidad}` - Eliminar unidad

### Insumos
- `GET /api/insumos` - Listar insumos
- `GET /api/insumos/{insumo}` - Obtener insumo
- `POST /api/insumos` - Crear insumo
- `PUT /api/insumos/{insumo}` - Actualizar insumo
- `DELETE /api/insumos/{insumo}` - Eliminar insumo
- `POST /api/insumos/{insumo}/adjust-stock` - Ajustar stock
- `GET /api/insumos/unidades-medida` - Obtener unidades de medida
- `GET /api/insumos/low-stock` - Obtener insumos con stock bajo

### Inventarios
- `GET /api/inventarios` - Listar inventarios
- `GET /api/inventarios/{inventario}` - Obtener inventario
- `POST /api/inventarios` - Crear inventario
- `PUT /api/inventarios/{inventario}` - Actualizar inventario
- `DELETE /api/inventarios/{inventario}` - Eliminar inventario
- `GET /api/inventarios/insumos` - Obtener insumos

### Proveedores
- `GET /api/proveedores` - Listar proveedores
- `GET /api/proveedores/{proveedor}` - Obtener proveedor
- `POST /api/proveedores` - Crear proveedor
- `PUT /api/proveedores/{proveedor}` - Actualizar proveedor
- `DELETE /api/proveedores/{proveedor}` - Eliminar proveedor
- `GET /api/proveedores/select` - Obtener proveedores para select

### Facturas
- `GET /api/facturas` - Listar facturas
- `GET /api/facturas/{factura}` - Obtener factura
- `POST /api/facturas` - Crear factura
- `PUT /api/facturas/{factura}` - Actualizar factura
- `DELETE /api/facturas/{factura}` - Eliminar factura
- `GET /api/facturas/{factura}/download` - Descargar archivo
- `GET /api/facturas/proveedores` - Obtener proveedores

## Parámetros de Consulta

### Filtros Comunes
- `search` - Búsqueda por texto
- `per_page` - Elementos por página (default: 15)
- `page` - Número de página

### Filtros Específicos

#### Facturas
- `proveedor_id` - Filtrar por proveedor
- `fecha_desde` - Fecha desde
- `fecha_hasta` - Fecha hasta

#### Insumos
- `unidad` - Filtrar por unidad de medida
- `stock_status` - Estado del stock (low, out, normal)

#### Inventarios
- `insumo_id` - Filtrar por insumo
- `fecha_desde` - Fecha desde
- `fecha_hasta` - Fecha hasta

## Respuestas de Error

### Error de Validación (422)
```json
{
    "success": false,
    "message": "Datos de validación incorrectos",
    "errors": {
        "campo": ["El campo es requerido"]
    }
}
```

### Error de Autorización (403)
```json
{
    "success": false,
    "message": "No tienes permisos para realizar esta acción"
}
```

### Error del Servidor (500)
```json
{
    "success": false,
    "message": "Error interno del servidor"
}
```

## Headers Requeridos

Para todas las peticiones autenticadas:
```
Authorization: Bearer {token}
Content-Type: application/json
```

## Ejemplos de Uso

### Crear Usuario
```bash
curl -X POST http://localhost:8000/api/users \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "run": "12345678-9",
    "nombre": "Usuario Nuevo",
    "correo": "nuevo@ejemplo.com",
    "contrasena": "password123",
    "id_depto": "DEPT001",
    "permissions": ["manage-users", "manage-inventory"]
  }'
```

### Crear Factura
```bash
curl -X POST http://localhost:8000/api/facturas \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "numero_factura": "FAC-001",
    "proveedor_id": 1,
    "monto_total": 150000,
    "fecha_factura": "2025-10-23",
    "observaciones": "Factura de prueba"
  }'
```

### Ajustar Stock de Insumo
```bash
curl -X POST http://localhost:8000/api/insumos/INS001/adjust-stock \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "cantidad": 50,
    "tipo": "add",
    "observaciones": "Ajuste de stock por compra"
  }'
```
