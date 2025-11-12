<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Insumo extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_insumo',
        'codigo_barra',
        'nombre_insumo',
        'stock_actual',
        'id_unidad',
        'tipo_insumo_id',
    ];
    protected $table = 'insumos';
    protected $primaryKey = 'id_insumo';
    public $incrementing = false;
    protected $keyType = 'string';
    protected function casts(): array
    {
        return [
            'stock_actual' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'id_unidad', 'id_unidad');
    }
    public function departamentos(): BelongsToMany
    {
        return $this->belongsToMany(Departamento::class, 'departamento_insumo', 'id_insumo', 'id_depto')
            ->withTimestamps();
    }
    public function tipoInsumo(): BelongsTo
    {
        return $this->belongsTo(TipoInsumo::class, 'tipo_insumo_id');
    }
    public function getStockStatusAttribute(): string
    {
        return match (true) {
            $this->stock_actual <= 0 => 'agotado',
            default => 'normal',
        };
    }

    public function getStockStatusColorAttribute(): string
    {
        return match ($this->stock_status) {
            'agotado' => 'red',
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
        if (!$this->canReduceStock($quantity)) {
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
}
