<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TipoInsumo extends Model
{
    use HasFactory;
    protected $table = 'tipo_insumos';
    protected $fillable = ['nombre_tipo'];
    public function insumos(): HasMany
    {
        return $this->hasMany(Insumo::class);
    }
    public function scopeOrderByName($query)
    {
        return $query->orderBy('nombre_tipo');
    }
    public function getNombreAttribute(): string
    {
        return $this->nombre_tipo;
    }
    public function tieneInsumos(): bool
    {
        return $this->insumos()->exists();
    }
    public function getTotalInsumosAttribute(): int
    {
        return $this->insumos()->count();
    }
    public function getStockTotalAttribute(): int
    {
        return $this->insumos()->sum('stock_actual');
    }
}
