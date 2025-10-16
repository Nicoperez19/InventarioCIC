<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Detalle_Solicitud extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_detalle_solicitud',
        'id_solicitud',
        'id_producto',
        'cantidad_solicitud',
    ];

    protected $primaryKey = 'id_detalle_solicitud';
    public $incrementing = false;
    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'cantidad_solicitud' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Relaciones
    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'id_solicitud', 'id_solicitud');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    // Métodos de negocio
    public function canBeFulfilled(): bool
    {
        return $this->producto->canReduceStock($this->cantidad_solicitud);
    }

    public function fulfill(): bool
    {
        if (!$this->canBeFulfilled()) {
            return false;
        }

        return $this->producto->reduceStock($this->cantidad_solicitud);
    }

    public function getTotalValueAttribute(): float
    {
        // Si el producto tuviera precio, se calcularía aquí
        return $this->cantidad_solicitud;
    }

    // Scopes
    public function scopeBySolicitud($query, string $solicitudId)
    {
        return $query->where('id_solicitud', $solicitudId);
    }

    public function scopeByProducto($query, string $productoId)
    {
        return $query->where('id_producto', $productoId);
    }

    public function scopeWithProducto($query)
    {
        return $query->with('producto.unidad');
    }

    public function scopeWithSolicitud($query)
    {
        return $query->with('solicitud.usuario');
    }

    // Métodos estáticos
    public static function getBySolicitud(string $solicitudId)
    {
        return static::bySolicitud($solicitudId)->withProducto()->get();
    }

    public static function getByProducto(string $productoId)
    {
        return static::byProducto($productoId)->withSolicitud()->get();
    }
}
