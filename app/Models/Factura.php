<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Factura extends Model
{
    use HasFactory;
    protected $table = 'facturas';
    protected $fillable = [
        'numero_factura',
        'proveedor_id',
        'monto_total',
        'fecha_factura',
        'archivo_path',
        'archivo_nombre',
        'observaciones',
        'run'
    ];
    protected $casts = [
        'fecha_factura' => 'date',
        'monto_total' => 'decimal:2'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'run', 'run');
    }
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }
    public function getArchivoUrlAttribute(): ?string
    {
        if ($this->archivo_path) {
            return asset('storage/' . $this->archivo_path);
        }
        return null;
    }
    public function tieneArchivo(): bool
    {
        return !is_null($this->archivo_path);
    }
}
