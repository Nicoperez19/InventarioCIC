<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
class Solicitud extends Model
{
    use HasFactory;
    protected $table = 'solicitudes';
    protected $fillable = [
        'numero_solicitud',
        'tipo_solicitud',
        'observaciones',
        'estado',
        'user_id',
        'departamento_id',
        'tipo_insumo_id',
        'fecha_solicitud',
        'fecha_aprobacion',
        'fecha_entrega',
        'aprobado_por',
        'entregado_por'
    ];
    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_aprobacion' => 'datetime',
        'fecha_entrega' => 'datetime',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'run');
    }
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'id_depto');
    }
    public function tipoInsumo(): BelongsTo
    {
        return $this->belongsTo(TipoInsumo::class);
    }
    public function aprobadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por', 'run');
    }
    public function entregadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entregado_por', 'run');
    }
    public function items(): HasMany
    {
        return $this->hasMany(SolicitudItem::class);
    }
    public function insumos(): BelongsToMany
    {
        return $this->belongsToMany(Insumo::class, 'solicitud_items')
                    ->withPivot(['cantidad_solicitada', 'cantidad_aprobada', 'cantidad_entregada', 'observaciones_item', 'estado_item'])
                    ->withTimestamps();
    }
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }
    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobada');
    }
    public function scopeEntregadas($query)
    {
        return $query->where('estado', 'entregada');
    }
    public function scopePorDepartamento($query, $departamentoId)
    {
        return $query->where('departamento_id', $departamentoId);
    }
    public function scopePorTipoInsumo($query, $tipoInsumoId)
    {
        return $query->where('tipo_insumo_id', $tipoInsumoId);
    }
    public function scopeIndividuales($query)
    {
        return $query->where('tipo_solicitud', 'individual');
    }
    public function scopeMasivas($query)
    {
        return $query->where('tipo_solicitud', 'masiva');
    }
    public function generarNumeroSolicitud(): string
    {
        $fecha = now()->format('Ymd');
        $ultimoNumero = self::whereDate('created_at', now()->toDateString())
                           ->orderBy('id', 'desc')
                           ->value('numero_solicitud');
        if ($ultimoNumero) {
            $numero = (int) substr($ultimoNumero, -4) + 1;
        } else {
            $numero = 1;
        }
        return 'SOL-' . $fecha . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
    public function aprobar($userId): bool
    {
        $this->update([
            'estado' => 'aprobada',
            'aprobado_por' => $userId,
            'fecha_aprobacion' => now()
        ]);
        $this->items()->update([
            'estado_item' => 'aprobado',
            'cantidad_aprobada' => DB::raw('cantidad_solicitada')
        ]);
        return true;
    }
    public function rechazar($userId): bool
    {
        $this->update([
            'estado' => 'rechazada',
            'aprobado_por' => $userId,
            'fecha_aprobacion' => now()
        ]);
        $this->items()->update(['estado_item' => 'rechazado']);
        return true;
    }
    public function entregar($userId): bool
    {
        $this->update([
            'estado' => 'entregada',
            'entregado_por' => $userId,
            'fecha_entrega' => now()
        ]);
        $this->items()->update([
            'estado_item' => 'entregado',
            'cantidad_entregada' => DB::raw('cantidad_aprobada')
        ]);
        return true;
    }
    public function getTotalItemsAttribute(): int
    {
        return $this->items()->count();
    }
    public function getTotalCantidadSolicitadaAttribute(): int
    {
        return $this->items()->sum('cantidad_solicitada');
    }
    public function getTotalCantidadAprobadaAttribute(): int
    {
        return $this->items()->sum('cantidad_aprobada');
    }
    public function getTotalCantidadEntregadaAttribute(): int
    {
        return $this->items()->sum('cantidad_entregada');
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($solicitud) {
            if (empty($solicitud->numero_solicitud)) {
                $solicitud->numero_solicitud = $solicitud->generarNumeroSolicitud();
            }
        });
    }
}
