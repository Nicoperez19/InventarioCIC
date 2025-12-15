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
        $ultimoSolicitud = self::orderBy('id', 'desc')->first();
        if ($ultimoSolicitud && $ultimoSolicitud->numero_solicitud) {
            // Extraer el número del último formato SOL-XXX
            $parts = explode('-', $ultimoSolicitud->numero_solicitud);
            if (count($parts) >= 2 && is_numeric($parts[1])) {
                $numero = (int) $parts[1] + 1;
            } else {
                // Si no puede extraer, usar el ID + 1
                $numero = $ultimoSolicitud->id + 1;
            }
        } else {
            $numero = 1;
        }
        return 'SOL-' . $numero;
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
        return DB::transaction(function () use ($userId) {
            // Obtener los items con sus insumos para devolver el stock
            $items = $this->items()->with('insumo')->get();

            // Devolver el stock de cada insumo (solo si el item está pendiente o aprobado)
            foreach ($items as $item) {
                // Solo devolver stock si el item no ha sido entregado
                if ($item->estado_item === 'entregado') {
                    continue; // No devolver stock de items ya entregados
                }

                $insumo = $item->insumo;
                if (!$insumo) {
                    continue; // Si no existe el insumo, continuar
                }

                $cantidadADevolver = $item->cantidad_solicitada;
                
                if ($cantidadADevolver > 0) {
                    // Bloquear el insumo para evitar condiciones de carrera
                    $insumoLocked = Insumo::where('id_insumo', $insumo->id_insumo)
                        ->lockForUpdate()
                        ->first();
                    
                    if ($insumoLocked) {
                        // Devolver el stock
                        $insumoLocked->addStock($cantidadADevolver);
                    }
                }
            }

            // Actualizar el estado de la solicitud
            $this->update([
                'estado' => 'rechazada',
                'aprobado_por' => $userId,
                'fecha_aprobacion' => now()
            ]);

            // Actualizar los items
            $this->items()->update(['estado_item' => 'rechazado']);

            return true;
        });
    }
    public function entregar($userId): bool
    {
        // Verificar que la solicitud no haya sido entregada previamente
        if ($this->estado === 'entregada') {
            throw new \Exception('Esta solicitud ya ha sido entregada anteriormente.');
        }

        // Verificar que la solicitud esté aprobada
        if ($this->estado !== 'aprobada') {
            throw new \Exception('Solo se pueden entregar solicitudes aprobadas.');
        }

        return DB::transaction(function () use ($userId) {
            // Obtener solo los items aprobados
            $items = $this->items()
                ->where('estado_item', 'aprobado')
                ->get();

            if ($items->isEmpty()) {
                throw new \Exception('No hay items aprobados para entregar.');
            }

            // El stock ya fue reducido al crear la solicitud, solo actualizar estados
            foreach ($items as $item) {
                // Usar cantidad aprobada (si existe) o cantidad solicitada
                $cantidadEntregar = $item->cantidad_aprobada ?? $item->cantidad_solicitada;
                
                // Actualizar el item con la cantidad entregada
                $item->update([
                    'estado_item' => 'entregado',
                    'cantidad_entregada' => $cantidadEntregar
                ]);
            }

            // Actualizar el estado de la solicitud
            $this->update([
                'estado' => 'entregada',
                'entregado_por' => $userId,
                'fecha_entrega' => now()
            ]);

            return true;
        });
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
