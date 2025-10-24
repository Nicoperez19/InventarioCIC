<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoInsumo extends Model
{
    use HasFactory;

    protected $table = 'tipo_insumos';

    protected $fillable = [
        'nombre_tipo'
    ];

    /**
     * Relación con los insumos de este tipo
     */
    public function insumos(): HasMany
    {
        return $this->hasMany(Insumo::class);
    }

    /**
     * Scope para tipos activos (comentado - campo no existe)
     */
    // public function scopeActivos($query)
    // {
    //     return $query->where('activo', true);
    // }

    /**
     * Scope para ordenar por nombre
     */
    public function scopeOrderByName($query)
    {
        return $query->orderBy('nombre_tipo');
    }

    /**
     * Obtener el nombre del tipo
     */
    public function getNombreAttribute(): string
    {
        return $this->nombre_tipo;
    }

    /**
     * Verificar si el tipo tiene insumos
     */
    public function tieneInsumos(): bool
    {
        return $this->insumos()->exists();
    }

    /**
     * Obtener el total de insumos del tipo
     */
    public function getTotalInsumosAttribute(): int
    {
        return $this->insumos()->count();
    }

    /**
     * Obtener el stock total de insumos del tipo
     */
    public function getStockTotalAttribute(): int
    {
        return $this->insumos()->sum('stock_actual');
    }

    /**
     * Obtener el valor total de insumos del tipo
     */
    public function getValorTotalAttribute(): float
    {
        return $this->insumos()->sum('precio_unitario');
    }

    /**
     * Verificar si el tipo está activo (comentado - campo no existe)
     */
    // public function isActive(): bool
    // {
    //     return $this->activo;
    // }

    /**
     * Activar el tipo (comentado - campo no existe)
     */
    // public function activar(): void
    // {
    //     $this->update(['activo' => true]);
    // }

    /**
     * Desactivar el tipo (comentado - campo no existe)
     */
    // public function desactivar(): void
    // {
    //     $this->update(['activo' => false]);
    // }
}