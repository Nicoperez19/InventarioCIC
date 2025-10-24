# Ejemplo de Archivo Excel para Carga Masiva

## Estructura del Archivo

El sistema de carga masiva espera un archivo Excel (.xlsx, .xls) o CSV con la siguiente estructura:

### Hojas del Excel
- **Cada hoja** del Excel se convertirá en un "Tipo de Insumo"
- El nombre de la hoja será el nombre del tipo de insumo

### Estructura de cada hoja
- **Fila 4**: Contiene los headers de las columnas
- **Columna B (Fila 4+)**: Código del insumo (opcional, se genera automáticamente si está vacío)
- **Columna C (Fila 4+)**: Nombre del insumo (obligatorio)
- **Columna D (Fila 4+)**: Unidad de medida (se crea automáticamente si no existe)

## Ejemplo de Archivo

### Hoja 1: "Medicamentos"
```
A    | B           | C                    | D
-----|-------------|----------------------|----------
1    |             |                      |
2    |             |                      |
3    |             |                      |
4    | Código      | Nombre               | Unidad
5    | MED001      | Paracetamol 500mg    | Tableta
6    | MED002      | Ibuprofeno 400mg     | Tableta
7    | MED003      | Amoxicilina 500mg    | Cápsula
8    |             | Aspirina 100mg       | Tableta
```

### Hoja 2: "Materiales Quirúrgicos"
```
A    | B           | C                    | D
-----|-------------|----------------------|----------
1    |             |                      |
2    |             |                      |
3    |             |                      |
4    | Código      | Nombre               | Unidad
5    | MAT001      | Guantes quirúrgicos  | Par
6    | MAT002      | Jeringas 5ml         | Unidad
7    | MAT003      | Agujas 21G           | Unidad
8    |             | Gasas estériles      | Unidad
```

### Hoja 3: "Equipos Médicos"
```
A    | B           | C                    | D
-----|-------------|----------------------|----------
1    |             |                      |
2    |             |                      |
3    |             |                      |
4    | Código      | Nombre               | Unidad
5    | EQU001      | Estetoscopio         | Unidad
6    | EQU002      | Termómetro digital   | Unidad
7    | EQU003      | Tensiómetro          | Unidad
8    |             | Oxímetro de pulso    | Unidad
```

## Resultado Esperado

Después de procesar este archivo, el sistema creará:

### Tipos de Insumo:
1. **Medicamentos** (con color aleatorio)
2. **Materiales Quirúrgicos** (con color aleatorio)
3. **Equipos Médicos** (con color aleatorio)

### Insumos:
- **Medicamentos**: 4 insumos (Paracetamol, Ibuprofeno, Amoxicilina, Aspirina)
- **Materiales Quirúrgicos**: 4 insumos (Guantes, Jeringas, Agujas, Gasas)
- **Equipos Médicos**: 4 insumos (Estetoscopio, Termómetro, Tensiómetro, Oxímetro)

## Notas Importantes

1. **Códigos automáticos**: Si la columna B está vacía, el sistema generará códigos automáticamente (formato: INS-XXXXXXXX)
2. **Unidades de medida**: Se crean automáticamente si no existen
3. **IDs de insumos**: Se generan automáticamente (formato: INS000001, INS000002, etc.)
4. **Stock inicial**: Todos los insumos se crean con stock 0
5. **Departamento**: Se deja como null inicialmente

## Formato de Archivo Soportado

- **Excel**: .xlsx, .xls
- **CSV**: .csv
- **Tamaño máximo**: 10MB

