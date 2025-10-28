<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class Departamento extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'departamentos';
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
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_depto', 'id_depto');
    }
    public function insumos(): BelongsToMany
    {
        return $this->belongsToMany(Insumo::class, 'departamento_insumo', 'id_depto', 'id_insumo')
            ->withTimestamps();
    }
    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'departamento_id', 'id_depto');
    }
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
        return ! $this->hasActiveUsers();
    }
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
