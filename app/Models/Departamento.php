<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_depto',
        'nombre_depto',
    ];

    protected $primaryKey = 'id_depto';
    public $incrementing = false;
    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    // Relaciones
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_depto', 'id_depto');
    }

    // Métodos de negocio
    public function getActiveUsersCountAttribute(): int
    {
        return $this->users()->whereNull('deleted_at')->count();
    }

    public function getTotalUsersCountAttribute(): int
    {
        return $this->users()->count();
    }

    public function hasActiveUsers(): bool
    {
        return $this->active_users_count > 0;
    }

    public function canBeDeleted(): bool
    {
        return !$this->hasActiveUsers();
    }

    // Scopes
    public function scopeWithActiveUsers($query)
    {
        return $query->whereHas('users', function ($q) {
            $q->whereNull('deleted_at');
        });
    }

    public function scopeWithoutUsers($query)
    {
        return $query->whereDoesntHave('users');
    }

    public function scopeOrderByName($query, string $direction = 'asc')
    {
        return $query->orderBy('nombre_depto', $direction);
    }

    public function scopeWithUsersCount($query)
    {
        return $query->withCount('users');
    }

    // Métodos estáticos
    public static function getWithUsers()
    {
        return static::withActiveUsers()->withUsersCount()->orderByName()->get();
    }

    public static function getEmpty()
    {
        return static::withoutUsers()->orderByName()->get();
    }

    public static function findByName(string $nombre): ?self
    {
        return static::where('nombre_depto', $nombre)->first();
    }

    public static function createDepartamento(array $data): self
    {
        return static::create($data);
    }
}
