<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Proveedor extends Model
{
    use HasFactory;
    protected $table = 'proveedores';
    protected $fillable = [
        'rut',
        'nombre_proveedor',
        'telefono'
    ];
    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }
    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre_proveedor;
    }
    public function getRutFormateadoAttribute(): string
    {
        $rut = $this->rut;
        if (strlen($rut) > 1) {
            $rut = number_format(substr($rut, 0, -1), 0, '', '.') . '-' . substr($rut, -1);
        }
        return $rut;
    }
    public function tieneFacturas(): bool
    {
        return $this->facturas()->exists();
    }
    public function getTotalFacturasAttribute(): int
    {
        return $this->facturas()->count();
    }
    public function getMontoTotalFacturasAttribute(): float
    {
        return $this->facturas()->sum('monto_total');
    }
}
