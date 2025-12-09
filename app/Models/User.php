<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, HasApiTokens;
    protected $primaryKey = 'run';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'run',
        'nombre',
        'correo',
        'contrasena',
        'id_depto',
        'codigo_barra',
    ];
    
    /**
     * Obtener el nombre de la columna que se usa para el route model binding
     */
    public function getRouteKeyName(): string
    {
        return 'run';
    }
    
    /**
     * Resolver el modelo desde la ruta usando el RUN
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('run', $value)->firstOrFail();
    }
    protected $hidden = [
        'contrasena',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'correo_verificado_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
    
    /**
     * Hash de la contraseña al establecerla
     */
    public function setContrasenaAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['contrasena'] = Hash::make($value);
        }
    }
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_depto', 'id_depto');
    }
    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class, 'run', 'run');
    }
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
    public function getNameAttribute(): string
    {
        return $this->attributes['nombre'];
    }
    public function getEmailAttribute(): string
    {
        return $this->attributes['correo'];
    }
    // NOTA: Se eliminó getPasswordAttribute() por seguridad
    // La contraseña debe accederse solo a través de getAuthPassword() o directamente con $user->contrasena
    // y está oculta en el array $hidden para evitar exposición en JSON
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
    public static function getActiveUsers()
    {
        return static::with('departamento');
    }
    public static function getUsersByDepartment(string $departmentId)
    {
        return static::where('id_depto', $departmentId)->with('departamento');
    }
    public function isActive(): bool
    {
        return true;
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
    public function canManageDepartments(): bool
    {
        return $this->hasPermissionTo('manage-departments') || $this->hasRole('admin');
    }
    public function canManageProducts(): bool
    {
        return $this->hasPermissionTo('manage-products') || $this->hasRole('admin');
    }
    public function canManageRequests(): bool
    {
        return $this->hasPermissionTo('manage-requests') || $this->hasRole('admin');
    }
    public function hasValidRun(): bool
    {
        $run = str_replace(['.', '-'], '', $this->run);
        return preg_match('/^[0-9]{7,8}[0-9kK]$/', $run);
    }
    public function hasValidEmail(): bool
    {
        return filter_var($this->correo, FILTER_VALIDATE_EMAIL) !== false;
    }
    public function getDepartmentNameAttribute(): string
    {
        return $this->departamento->nombre_depto ?? 'Sin departamento';
    }
    public function getPermissionsListAttribute(): array
    {
        return $this->permissions->pluck('name')->toArray();
    }
    public function getRolesListAttribute(): array
    {
        return $this->roles->pluck('name')->toArray();
    }
    public function scopeActive($query)
    {
        return $query;
    }
    public function scopeInactive($query)
    {
        return $query->whereRaw('1 = 0'); // Siempre vacío ya que no hay soft deletes
    }
    public function scopeByDepartment($query, string $departmentId)
    {
        return $query->where('id_depto', $departmentId);
    }
    public function scopeWithPermissions($query)
    {
        return $query->with('permissions');
    }
    public function scopeWithRoles($query)
    {
        return $query->with('roles');
    }
    public function scopeOrderByName($query, string $direction = 'asc')
    {
        return $query->orderBy('nombre', $direction);
    }
    public function scopeOrderByDepartment($query, string $direction = 'asc')
    {
        return $query->join('departamentos', 'users.id_depto', '=', 'departamentos.id_depto')
                    ->orderBy('departamentos.nombre_depto', $direction)
                    ->select('users.*');
    }
}
