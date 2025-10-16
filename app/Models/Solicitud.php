<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitud extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_solicitud',
        'fecha_solicitud',
        'estado_solicitud',
        'observaciones',
        'id_usuario',
    ];

    protected $primaryKey = 'id_solicitud';
    public $incrementing = false;
    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'fecha_solicitud' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Constantes para estados
    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_APROBADA = 'aprobada';
    public const ESTADO_RECHAZADA = 'rechazada';
    public const ESTADO_ENTREGADA = 'entregada';

    // Relaciones
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'run');
    }

    public function detalleSolicitudes(): HasMany
    {
        return $this->hasMany(Detalle_Solicitud::class, 'id_solicitud', 'id_solicitud');
    }

    // Métodos de negocio
    public function isPendiente(): bool
    {
        return $this->estado_solicitud === self::ESTADO_PENDIENTE;
    }

    public function isAprobada(): bool
    {
        return $this->estado_solicitud === self::ESTADO_APROBADA;
    }

    public function isRechazada(): bool
    {
        return $this->estado_solicitud === self::ESTADO_RECHAZADA;
    }

    public function isEntregada(): bool
    {
        return $this->estado_solicitud === self::ESTADO_ENTREGADA;
    }

    public function canBeApproved(): bool
    {
        return $this->isPendiente();
    }

    public function canBeRejected(): bool
    {
        return $this->isPendiente();
    }

    public function canBeDelivered(): bool
    {
        return $this->isAprobada();
    }

    public function approve(): bool
    {
        if (!$this->canBeApproved()) {
            return false;
        }

        $this->estado_solicitud = self::ESTADO_APROBADA;
        return $this->save();
    }

    public function reject(): bool
    {
        if (!$this->canBeRejected()) {
            return false;
        }

        $this->estado_solicitud = self::ESTADO_RECHAZADA;
        return $this->save();
    }

    public function deliver(): bool
    {
        if (!$this->canBeDelivered()) {
            return false;
        }

        $this->estado_solicitud = self::ESTADO_ENTREGADA;
        return $this->save();
    }

    public function getTotalProductosAttribute(): int
    {
        return $this->detalleSolicitudes()->sum('cantidad_solicitud');
    }

    public function getEstadoColorAttribute(): string
    {
        return match($this->estado_solicitud) {
            self::ESTADO_PENDIENTE => 'yellow',
            self::ESTADO_APROBADA => 'blue',
            self::ESTADO_RECHAZADA => 'red',
            self::ESTADO_ENTREGADA => 'green',
            default => 'gray'
        };
    }

    // Scopes
    public function scopeByEstado($query, string $estado)
    {
        return $query->where('estado_solicitud', $estado);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado_solicitud', self::ESTADO_PENDIENTE);
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado_solicitud', self::ESTADO_APROBADA);
    }

    public function scopeRechazadas($query)
    {
        return $query->where('estado_solicitud', self::ESTADO_RECHAZADA);
    }

    public function scopeEntregadas($query)
    {
        return $query->where('estado_solicitud', self::ESTADO_ENTREGADA);
    }

    public function scopeByUsuario($query, string $usuarioId)
    {
        return $query->where('id_usuario', $usuarioId);
    }

    public function scopeByFecha($query, string $fecha)
    {
        return $query->whereDate('fecha_solicitud', $fecha);
    }

    public function scopeByFechaRange($query, string $fechaInicio, string $fechaFin)
    {
        return $query->whereBetween('fecha_solicitud', [$fechaInicio, $fechaFin]);
    }

    // Métodos estáticos
    public static function getEstadosDisponibles(): array
    {
        return [
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_APROBADA => 'Aprobada',
            self::ESTADO_RECHAZADA => 'Rechazada',
            self::ESTADO_ENTREGADA => 'Entregada',
        ];
    }

    public static function getPendientes()
    {
        return static::pendientes()->with(['usuario.departamento', 'detalleSolicitudes.producto'])->get();
    }

    public static function getByUsuario(string $usuarioId)
    {
        return static::byUsuario($usuarioId)->with(['detalleSolicitudes.producto'])->get();
    }
}
