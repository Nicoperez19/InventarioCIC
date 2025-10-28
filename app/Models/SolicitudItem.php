<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class SolicitudItem extends Model
{
    use HasFactory;
    protected $table = 'solicitud_items';
    protected $fillable = [
        'solicitud_id',
        'insumo_id',
        'cantidad_solicitada',
        'cantidad_aprobada',
        'cantidad_entregada',
        'observaciones_item',
        'estado_item'
    ];
    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class);
    }
    public function insumo(): BelongsTo
    {
        return $this->belongsTo(Insumo::class, 'insumo_id', 'id_insumo');
    }
    public function scopePendientes($query)
    {
        return $query->where('estado_item', 'pendiente');
    }
    public function scopeAprobados($query)
    {
        return $query->where('estado_item', 'aprobado');
    }
    public function scopeRechazados($query)
    {
        return $query->where('estado_item', 'rechazado');
    }
    public function scopeEntregados($query)
    {
        return $query->where('estado_item', 'entregado');
    }
    public function aprobar($cantidadAprobada = null): bool
    {
        $cantidad = $cantidadAprobada ?? $this->cantidad_solicitada;
        $this->update([
            'estado_item' => 'aprobado',
            'cantidad_aprobada' => $cantidad
        ]);
        return true;
    }
    public function rechazar(): bool
    {
        $this->update(['estado_item' => 'rechazado']);
        return true;
    }
    public function entregar($cantidadEntregada = null): bool
    {
        $cantidad = $cantidadEntregada ?? $this->cantidad_aprobada;
        $this->update([
            'estado_item' => 'entregado',
            'cantidad_entregada' => $cantidad
        ]);
        return true;
    }
    public function getCantidadPendienteAttribute(): int
    {
        return $this->cantidad_aprobada - $this->cantidad_entregada;
    }
    public function getEstaCompletamenteEntregadoAttribute(): bool
    {
        return $this->cantidad_entregada >= $this->cantidad_aprobada;
    }
}
