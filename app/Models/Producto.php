<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_producto',
        'codigo_barra',
        'nombre_producto',
        'stock_minimo',
        'stock_actual',
        'observaciones',
        'id_unidad',
    ];

    protected $primaryKey = 'id_producto';

    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'stock_minimo' => 'integer',
            'stock_actual' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relaciones
    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'id_unidad', 'id_unidad');
    }

    public function inventarios(): HasMany
    {
        return $this->hasMany(Inventario::class, 'id_producto', 'id_producto');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(Movimientos::class, 'id_producto', 'id_producto');
    }

    public function detalleSolicitudes(): HasMany
    {
        return $this->hasMany(Detalle_Solicitud::class, 'id_producto', 'id_producto');
    }

    public function departamentos(): BelongsToMany
    {
        return $this->belongsToMany(Departamento::class, 'departamento_producto', 'id_producto', 'id_depto')
            ->withTimestamps();
    }

    // Métodos de negocio
    public function isLowStock(): bool
    {
        return $this->stock_actual <= $this->stock_minimo;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock_actual <= 0) {
            return 'agotado';
        } elseif ($this->isLowStock()) {
            return 'bajo';
        }

        return 'normal';
    }

    public function getStockStatusColorAttribute(): string
    {
        return match ($this->stock_status) {
            'agotado' => 'red',
            'bajo' => 'yellow',
            'normal' => 'green',
            default => 'gray'
        };
    }

    public function canReduceStock(int $quantity): bool
    {
        return $this->stock_actual >= $quantity;
    }

    public function reduceStock(int $quantity): bool
    {
        if (! $this->canReduceStock($quantity)) {
            return false;
        }

        $this->stock_actual -= $quantity;

        return $this->save();
    }

    public function addStock(int $quantity): bool
    {
        $this->stock_actual += $quantity;

        return $this->save();
    }

    // Scopes
    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock_actual <= stock_minimo');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_actual', '<=', 0);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_actual', '>', 0);
    }

    public function scopeByUnidad($query, string $unidadId)
    {
        return $query->where('id_unidad', $unidadId);
    }

    // Métodos estáticos

    public static function getLowStockProducts()
    {
        return static::lowStock()->with('unidad')->get();
    }

    public static function getOutOfStockProducts()
    {
        return static::outOfStock()->with('unidad')->get();
    }
}
