<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadMedida extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_unidad',
        'nombre_unidad_medida',
    ];

    protected $table = 'unidad_medidas';

    protected $primaryKey = 'id_unidad';

    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relaciones
    public function insumos(): HasMany
    {
        return $this->hasMany(Insumo::class, 'id_unidad', 'id_unidad');
    }

    // Métodos de negocio
    public function getActiveInsumosCountAttribute(): int
    {
        return $this->insumos()->whereNull('deleted_at')->count();
    }

    public function getTotalInsumosCountAttribute(): int
    {
        return $this->insumos()->count();
    }

    public function hasActiveInsumos(): bool
    {
        return $this->active_insumos_count > 0;
    }

    public function canBeDeleted(): bool
    {
        return ! $this->hasActiveInsumos();
    }

    public function getTotalStockAttribute(): int
    {
        return $this->insumos()->sum('stock_actual');
    }

    public function getLowStockInsumosCountAttribute(): int
    {
        return $this->insumos()->whereRaw('stock_actual <= stock_minimo')->count();
    }

    public function getOutOfStockInsumosCountAttribute(): int
    {
        return $this->insumos()->where('stock_actual', '<=', 0)->count();
    }

    // Scopes
    public function scopeWithActiveInsumos($query)
    {
        return $query->whereHas('insumos', function ($q) {
            $q->whereNull('deleted_at');
        });
    }

    public function scopeWithoutInsumos($query)
    {
        return $query->whereDoesntHave('insumos');
    }

    public function scopeOrderByName($query, string $direction = 'asc')
    {
        return $query->orderBy('nombre_unidad_medida', $direction);
    }

    public function scopeWithInsumosCount($query)
    {
        return $query->withCount('insumos');
    }

    public function scopeWithStockInfo($query)
    {
        return $query->withCount([
            'insumos as total_insumos',
            'insumos as low_stock_insumos' => function ($q) {
                $q->whereRaw('stock_actual <= stock_minimo');
            },
            'insumos as out_of_stock_insumos' => function ($q) {
                $q->where('stock_actual', '<=', 0);
            },
        ]);
    }

    // Métodos estáticos
    public static function getWithInsumos()
    {
        return static::withActiveInsumos()->withStockInfo()->orderByName()->get();
    }

    public static function getEmpty()
    {
        return static::withoutInsumos()->orderByName()->get();
    }

    public static function findByName(string $nombre): ?self
    {
        return static::where('nombre_unidad_medida', $nombre)->first();
    }

    public static function createUnidadMedida(array $data): self
    {
        return static::create($data);
    }

    public static function getWithLowStock()
    {
        return static::withActiveInsumos()
            ->whereHas('insumos', function ($q) {
                $q->whereRaw('stock_actual <= stock_minimo');
            })
            ->withStockInfo()
            ->orderByName()
            ->get();
    }
}
