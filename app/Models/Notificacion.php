<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    
    protected $fillable = [
        'tipo',
        'titulo',
        'mensaje',
        'user_id',
        'solicitud_id',
        'leida',
        'leida_at',
    ];

    protected $casts = [
        'leida' => 'boolean',
        'leida_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'run');
    }

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function marcarComoLeida(): void
    {
        $this->update([
            'leida' => true,
            'leida_at' => now(),
        ]);
    }

    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    public function scopeParaUsuario($query, string $userId)
    {
        return $query->where('user_id', $userId);
    }
}
