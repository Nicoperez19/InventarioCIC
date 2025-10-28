<?php
namespace App\Services;
use App\Models\User;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
class UserService
{
    public function createUser(array $data): User
    {
        try {
            return DB::transaction(function () use ($data) {
                $data['contrasena'] = Hash::make($data['contrasena']);
                $user = User::create($data);
                if (isset($data['permissions'])) {
                    $this->syncUserPermissions($user, $data['permissions']);
                }
                Log::info('Usuario creado exitosamente', [
                    'user_run' => $user->run,
                    'user_name' => $user->nombre,
                    'created_by' => auth()->id(),
                ]);
                return $user;
            });
        } catch (\Exception $e) {
            Log::error('Error creando usuario', [
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
    public function updateUser(User $user, array $data): User
    {
        try {
            return DB::transaction(function () use ($user, $data) {
                if (!empty($data['contrasena'])) {
                    $data['contrasena'] = Hash::make($data['contrasena']);
                } else {
                    unset($data['contrasena']);
                }
                $user->update($data);
                if (isset($data['permissions'])) {
                    $this->syncUserPermissions($user, $data['permissions']);
                }
                Log::info('Usuario actualizado exitosamente', [
                    'user_run' => $user->run,
                    'user_name' => $user->nombre,
                    'updated_by' => auth()->id(),
                ]);
                return $user;
            });
        } catch (\Exception $e) {
            Log::error('Error actualizando usuario', [
                'user_run' => $user->run,
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
    public function deleteUser(User $user): bool
    {
        try {
            DB::transaction(function () use ($user) {
                $user->delete();
                Log::info('Usuario eliminado exitosamente', [
                    'user_run' => $user->run,
                    'user_name' => $user->nombre,
                    'deleted_by' => auth()->id(),
                ]);
            });
            return true;
        } catch (\Exception $e) {
            Log::error('Error eliminando usuario', [
                'user_run' => $user->run,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    public function syncUserPermissions(User $user, array $permissionIds): void
    {
        try {
            if (empty($permissionIds)) {
                $user->syncPermissions([]);
                return;
            }
            $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $user->syncPermissions($permissionNames);
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            Log::info('Permisos sincronizados para usuario', [
                'user_run' => $user->run,
                'permissions_count' => count($permissionNames),
            ]);
        } catch (\Exception $e) {
            Log::error('Error sincronizando permisos', [
                'user_run' => $user->run,
                'permission_ids' => $permissionIds,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
    public function getUsersWithDepartments(): \Illuminate\Database\Eloquent\Collection
    {
        return User::with('departamento')->get();
    }
    public function getUsersByDepartment(string $departmentId): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('id_depto', $departmentId)->with('departamento')->get();
    }
    public function getUserStats(): array
    {
        return [
            'total_usuarios' => User::count(),
            'usuarios_activos' => User::whereNull('deleted_at')->count(),
            'usuarios_eliminados' => User::onlyTrashed()->count(),
            'usuarios_por_departamento' => User::selectRaw('id_depto, COUNT(*) as count')
                ->groupBy('id_depto')
                ->with('departamento')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->departamento->nombre_depto ?? 'Sin departamento' => $item->count];
                }),
        ];
    }
    public function runExists(string $run, ?User $excludeUser = null): bool
    {
        $query = User::where('run', $run);
        if ($excludeUser) {
            $query->where('run', '!=', $excludeUser->run);
        }
        return $query->exists();
    }
    public function emailExists(string $email, ?User $excludeUser = null): bool
    {
        $query = User::where('correo', $email);
        if ($excludeUser) {
            $query->where('run', '!=', $excludeUser->run);
        }
        return $query->exists();
    }
    public function getUsersWithPermission(string $permission): \Illuminate\Database\Eloquent\Collection
    {
        return User::permission($permission)->with('departamento')->get();
    }
    public function getUsersWithRole(string $role): \Illuminate\Database\Eloquent\Collection
    {
        return User::role($role)->with('departamento')->get();
    }
    public function changePassword(User $user, string $newPassword): bool
    {
        try {
            $user->update([
                'contrasena' => Hash::make($newPassword)
            ]);
            Log::info('Contraseña cambiada exitosamente', [
                'user_run' => $user->run,
                'changed_by' => auth()->id(),
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error cambiando contraseña', [
                'user_run' => $user->run,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
