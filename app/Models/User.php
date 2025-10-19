<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $primaryKey = 'run';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'run',
        'nombre',
        'correo',
        'contrasena',
        'id_depto',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'correo_verificado_at' => 'datetime',
            'contrasena' => 'hashed',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relaciones
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_depto', 'id_depto');
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'id_usuario', 'run');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(Movimientos::class, 'id_usuario', 'run');
    }

    // Métodos de autenticación personalizados
    public function getAuthPassword(): string
    {
        return $this->contrasena;
    }

    public function getAuthIdentifierName(): string
    {
        return 'run';
    }

    public function getAuthIdentifier(): string
    {
        return $this->run;
    }

    public function getEmailForPasswordReset(): string
    {
        return $this->correo;
    }

    public function getEmailForVerification(): string
    {
        return $this->correo;
    }

    // Accessors
    public function getNameAttribute(): string
    {
        return $this->attributes['nombre'];
    }

    public function getEmailAttribute(): string
    {
        return $this->attributes['correo'];
    }

    public function getPasswordAttribute(): string
    {
        return $this->attributes['contrasena'];
    }

    // Métodos estáticos de búsqueda
    public static function findByEmail(string $email): ?self
    {
        return static::where('correo', $email)->first();
    }

    public static function findByEmailOrFail(string $email): self
    {
        return static::where('correo', $email)->firstOrFail();
    }

    public static function findByRun(string $run): ?self
    {
        return static::where('run', $run)->first();
    }

    // Métodos de negocio
    public function isActive(): bool
    {
        return $this->deleted_at === null;
    }

    public function getFullNameAttribute(): string
    {
        return $this->nombre;
    }

    public function canManageInventory(): bool
    {
        return $this->hasPermissionTo('manage-inventory') || $this->hasRole('admin');
    }

    public function canManageUsers(): bool
    {
        return $this->hasPermissionTo('manage-users') || $this->hasRole('admin');
    }
}
