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

    /**
     * Relación con las facturas del proveedor
     */
    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }

    /**
     * Obtener el nombre completo del proveedor
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre_proveedor;
    }

    /**
     * Formatear RUT para mostrar
     */
    public function getRutFormateadoAttribute(): string
    {
        $rut = $this->rut;
        if (strlen($rut) > 1) {
            $rut = number_format(substr($rut, 0, -1), 0, '', '.') . '-' . substr($rut, -1);
        }
        return $rut;
    }

    /**
     * Verificar si el proveedor tiene facturas
     */
    public function tieneFacturas(): bool
    {
        return $this->facturas()->exists();
    }

    /**
     * Obtener el total de facturas del proveedor
     */
    public function getTotalFacturasAttribute(): int
    {
        return $this->facturas()->count();
    }

    /**
     * Obtener el monto total de facturas del proveedor
     */
    public function getMontoTotalFacturasAttribute(): float
    {
        return $this->facturas()->sum('monto_total');
    }
}
