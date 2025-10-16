<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimientos extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_movimiento',
        'tipo_movimiento',
        'cantidad',
        'fecha_movimiento',
        'observaciones',
        'id_producto',
        'id_usuario',
    ];

    protected $primaryKey = 'id_movimiento';

    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'fecha_movimiento' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Constantes para tipos de movimiento
    public const TIPO_ENTRADA = 'entrada';

    public const TIPO_SALIDA = 'salida';

    public const TIPO_AJUSTE = 'ajuste';

    public const TIPO_INVENTARIO = 'inventario';

    // Relaciones
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'run');
    }

    // Métodos de negocio
    public function isEntrada(): bool
    {
        return $this->tipo_movimiento === self::TIPO_ENTRADA;
    }

    public function isSalida(): bool
    {
        return $this->tipo_movimiento === self::TIPO_SALIDA;
    }

    public function isAjuste(): bool
    {
        return $this->tipo_movimiento === self::TIPO_AJUSTE;
    }

    public function isInventario(): bool
    {
        return $this->tipo_movimiento === self::TIPO_INVENTARIO;
    }

    public function getTipoColorAttribute(): string
    {
        return match ($this->tipo_movimiento) {
            self::TIPO_ENTRADA => 'green',
            self::TIPO_SALIDA => 'red',
            self::TIPO_AJUSTE => 'blue',
            self::TIPO_INVENTARIO => 'yellow',
            default => 'gray'
        };
    }

    public function getTipoIconAttribute(): string
    {
        return match ($this->tipo_movimiento) {
            self::TIPO_ENTRADA => 'arrow-up',
            self::TIPO_SALIDA => 'arrow-down',
            self::TIPO_AJUSTE => 'adjustments',
            self::TIPO_INVENTARIO => 'clipboard-list',
            default => 'question-mark'
        };
    }

    // Scopes
    public function scopeByTipo($query, string $tipo)
    {
        return $query->where('tipo_movimiento', $tipo);
    }

    public function scopeEntradas($query)
    {
        return $query->where('tipo_movimiento', self::TIPO_ENTRADA);
    }

    public function scopeSalidas($query)
    {
        return $query->where('tipo_movimiento', self::TIPO_SALIDA);
    }

    public function scopeAjustes($query)
    {
        return $query->where('tipo_movimiento', self::TIPO_AJUSTE);
    }

    public function scopeInventarios($query)
    {
        return $query->where('tipo_movimiento', self::TIPO_INVENTARIO);
    }

    public function scopeByProducto($query, string $productoId)
    {
        return $query->where('id_producto', $productoId);
    }

    public function scopeByUsuario($query, string $usuarioId)
    {
        return $query->where('id_usuario', $usuarioId);
    }

    public function scopeByFecha($query, string $fecha)
    {
        return $query->whereDate('fecha_movimiento', $fecha);
    }

    public function scopeByFechaRange($query, string $fechaInicio, string $fechaFin)
    {
        return $query->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['producto.unidad', 'usuario.departamento']);
    }

    // Métodos estáticos
    public static function getTiposDisponibles(): array
    {
        return [
            self::TIPO_ENTRADA => 'Entrada',
            self::TIPO_SALIDA => 'Salida',
            self::TIPO_AJUSTE => 'Ajuste',
            self::TIPO_INVENTARIO => 'Inventario',
        ];
    }

    public static function getByProducto(string $productoId)
    {
        return static::byProducto($productoId)->withRelations()->orderBy('fecha_movimiento', 'desc')->get();
    }

    public static function getByUsuario(string $usuarioId)
    {
        return static::byUsuario($usuarioId)->withRelations()->orderBy('fecha_movimiento', 'desc')->get();
    }

    public static function getByFechaRange(string $fechaInicio, string $fechaFin)
    {
        return static::byFechaRange($fechaInicio, $fechaFin)->withRelations()->orderBy('fecha_movimiento', 'desc')->get();
    }

    public static function createMovimiento(array $data): self
    {
        return static::create($data);
    }
}
