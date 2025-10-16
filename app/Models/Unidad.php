<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unidad extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_unidad',
        'nombre_unidad',
    ];

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
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'id_unidad', 'id_unidad');
    }

    // Métodos de negocio
    public function getActiveProductsCountAttribute(): int
    {
        return $this->productos()->whereNull('deleted_at')->count();
    }

    public function getTotalProductsCountAttribute(): int
    {
        return $this->productos()->count();
    }

    public function hasActiveProducts(): bool
    {
        return $this->active_products_count > 0;
    }

    public function canBeDeleted(): bool
    {
        return !$this->hasActiveProducts();
    }

    public function getTotalStockAttribute(): int
    {
        return $this->productos()->sum('stock_actual');
    }

    public function getLowStockProductsCountAttribute(): int
    {
        return $this->productos()->whereRaw('stock_actual <= stock_minimo')->count();
    }

    public function getOutOfStockProductsCountAttribute(): int
    {
        return $this->productos()->where('stock_actual', '<=', 0)->count();
    }

    // Scopes
    public function scopeWithActiveProducts($query)
    {
        return $query->whereHas('productos', function ($q) {
            $q->whereNull('deleted_at');
        });
    }

    public function scopeWithoutProducts($query)
    {
        return $query->whereDoesntHave('productos');
    }

    public function scopeOrderByName($query, string $direction = 'asc')
    {
        return $query->orderBy('nombre_unidad', $direction);
    }

    public function scopeWithProductsCount($query)
    {
        return $query->withCount('productos');
    }

    public function scopeWithStockInfo($query)
    {
        return $query->withCount([
            'productos as total_products',
            'productos as low_stock_products' => function ($q) {
                $q->whereRaw('stock_actual <= stock_minimo');
            },
            'productos as out_of_stock_products' => function ($q) {
                $q->where('stock_actual', '<=', 0);
            }
        ]);
    }

    // Métodos estáticos
    public static function getWithProducts()
    {
        return static::withActiveProducts()->withStockInfo()->orderByName()->get();
    }

    public static function getEmpty()
    {
        return static::withoutProducts()->orderByName()->get();
    }

    public static function findByName(string $nombre): ?self
    {
        return static::where('nombre_unidad', $nombre)->first();
    }

    public static function createUnidad(array $data): self
    {
        return static::create($data);
    }

    public static function getWithLowStock()
    {
        return static::withActiveProducts()
            ->whereHas('productos', function ($q) {
                $q->whereRaw('stock_actual <= stock_minimo');
            })
            ->withStockInfo()
            ->orderByName()
            ->get();
    }
}
