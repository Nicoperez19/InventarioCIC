<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventario extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_inventario',
        'id_producto',
        'fecha_inventario',
        'cantidad_inventario',
    ];

    protected $primaryKey = 'id_inventario';

    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'cantidad_inventario' => 'integer',
            'fecha_inventario' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Relaciones
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    // Métodos de negocio
    public function getDiferenciaStockAttribute(): int
    {
        return $this->cantidad_inventario - $this->producto->stock_actual;
    }


    public function applyToProduct(): bool
    {
        // Aplicar inventario al producto

        $producto = $this->producto;
        $producto->stock_actual = $this->cantidad_inventario;

        if ($producto->save()) {
            // Registrar movimiento de ajuste
            Movimientos::createMovimiento([
                'id_movimiento' => uniqid('MOV_'),
                'tipo_movimiento' => Movimientos::TIPO_AJUSTE,
                'cantidad' => abs($this->diferencia_stock),
                'fecha_movimiento' => now(),
                'observaciones' => "Ajuste por inventario - ID: {$this->id_inventario}",
                'id_producto' => $this->id_producto,
                'id_usuario' => auth()->id(),
            ]);

            return true;
        }

        return false;
    }

    // Scopes
    public function scopeByProducto($query, string $productoId)
    {
        return $query->where('id_producto', $productoId);
    }

    public function scopeByFecha($query, string $fecha)
    {
        return $query->whereDate('fecha_inventario', $fecha);
    }

    public function scopeByFechaRange($query, string $fechaInicio, string $fechaFin)
    {
        return $query->whereBetween('fecha_inventario', [$fechaInicio, $fechaFin]);
    }


    public function scopeWithProducto($query)
    {
        return $query->with('producto.unidad');
    }

    public function scopeOrderByFecha($query, string $direction = 'desc')
    {
        return $query->orderBy('fecha_inventario', $direction);
    }

    // Métodos estáticos
    public static function getByProducto(string $productoId)
    {
        return static::byProducto($productoId)->withProducto()->orderByFecha()->get();
    }


    public static function getByFechaRange(string $fechaInicio, string $fechaFin)
    {
        return static::byFechaRange($fechaInicio, $fechaFin)->withProducto()->orderByFecha()->get();
    }

    public static function createInventario(array $data): self
    {
        return static::create($data);
    }

    public static function getLatestByProducto(string $productoId): ?self
    {
        return static::byProducto($productoId)->orderByFecha()->first();
    }
}
