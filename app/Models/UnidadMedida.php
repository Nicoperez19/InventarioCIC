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
    public function insumos(): HasMany
    {
        return $this->hasMany(Insumo::class, 'id_unidad', 'id_unidad');
    }
    public function hasActiveInsumos(): bool
    {
        return $this->insumos()->whereNull('deleted_at')->exists();
    }
    public function scopeOrderByName($query, string $direction = 'asc')
    {
        return $query->orderBy('nombre_unidad_medida', $direction);
    }
    public static function findByName(string $nombre): ?self
    {
        return static::where('nombre_unidad_medida', $nombre)->first();
    }
}
