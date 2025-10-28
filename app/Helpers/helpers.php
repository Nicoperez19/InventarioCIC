<?php
if (!function_exists('translatePermission')) {
    function translatePermission(string $permissionName): string
    {
        $translations = [
            'manage-users' => 'Gestionar usuarios',
            'create-users' => 'Crear usuarios',
            'edit-users' => 'Editar usuarios',
            'delete-users' => 'Eliminar usuarios',
            'view-users' => 'Ver usuarios',
            'manage-inventory' => 'Gestionar inventario',
            'create-insumos' => 'Crear insumos',
            'edit-insumos' => 'Editar insumos',
            'delete-insumos' => 'Eliminar insumos',
            'view-insumos' => 'Ver insumos',
            'view-inventory' => 'Ver inventario',
            'create-inventory' => 'Crear inventario',
            'edit-inventory' => 'Editar inventario',
            'delete-inventory' => 'Eliminar inventario',
            'apply-inventory' => 'Aplicar inventario',
            'apply-all-inventory' => 'Aplicar todo el inventario',
            'view-inventory-discrepancies' => 'Ver discrepancias de inventario',
            'view-requests' => 'Ver solicitudes',
            'create-requests' => 'Crear solicitudes',
            'approve-requests' => 'Aprobar solicitudes',
            'reject-requests' => 'Rechazar solicitudes',
            'deliver-requests' => 'Entregar solicitudes',
            'view-pending-requests' => 'Ver solicitudes pendientes',
            'manage-departments' => 'Gestionar departamentos',
            'create-departments' => 'Crear departamentos',
            'edit-departments' => 'Editar departamentos',
            'delete-departments' => 'Eliminar departamentos',
            'view-departments' => 'Ver departamentos',
            'manage-units' => 'Gestionar unidades',
            'create-units' => 'Crear unidades',
            'edit-units' => 'Editar unidades',
            'delete-units' => 'Eliminar unidades',
            'view-units' => 'Ver unidades',
            'manage-roles' => 'Gestionar roles',
            'create-roles' => 'Crear roles',
            'edit-roles' => 'Editar roles',
            'delete-roles' => 'Eliminar roles',
            'view-roles' => 'Ver roles',
            'manage-permissions' => 'Gestionar permisos',
            'create-permissions' => 'Crear permisos',
            'edit-permissions' => 'Editar permisos',
            'delete-permissions' => 'Eliminar permisos',
            'view-permissions' => 'Ver permisos',
        ];
        return $translations[$permissionName] ?? $permissionName;
    }
}
if (!function_exists('groupPermissionsByContext')) {
    function groupPermissionsByContext($permissions): array
    {
        $grouped = [
            'Usuarios' => [
                'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                'permissions' => []
            ],
            'Productos' => [
                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                'permissions' => []
            ],
            'Inventario' => [
                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                'permissions' => []
            ],
            'Solicitudes' => [
                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'permissions' => []
            ],
            'Departamentos' => [
                'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                'permissions' => []
            ],
            'Unidades' => [
                'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
                'permissions' => []
            ],
            'Roles' => [
                'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                'permissions' => []
            ],
            'Permisos' => [
                'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                'permissions' => []
            ],
        ];
        foreach ($permissions as $permission) {
            $name = $permission->name;
            if (str_contains($name, 'user')) {
                $grouped['Usuarios']['permissions'][] = $permission;
            }
            elseif (str_contains($name, 'product')) {
                $grouped['Productos']['permissions'][] = $permission;
            }
            elseif (str_contains($name, 'inventory')) {
                $grouped['Inventario']['permissions'][] = $permission;
            }
            elseif (str_contains($name, 'request')) {
                $grouped['Solicitudes']['permissions'][] = $permission;
            }
            elseif (str_contains($name, 'department')) {
                $grouped['Departamentos']['permissions'][] = $permission;
            }
            elseif (str_contains($name, 'unit')) {
                $grouped['Unidades']['permissions'][] = $permission;
            }
            elseif (str_contains($name, 'role')) {
                $grouped['Roles']['permissions'][] = $permission;
            }
            elseif (str_contains($name, 'permission')) {
                $grouped['Permisos']['permissions'][] = $permission;
            }
        }
        return array_filter($grouped, function($group) {
            return !empty($group['permissions']);
        });
    }
}
if (!function_exists('getPermissionAction')) {
    function getPermissionAction(string $permissionName): string
    {
        if (str_starts_with($permissionName, 'view-')) return 'view';
        if (str_starts_with($permissionName, 'create-')) return 'create';
        if (str_starts_with($permissionName, 'edit-')) return 'edit';
        if (str_starts_with($permissionName, 'delete-')) return 'delete';
        if (str_starts_with($permissionName, 'manage-')) return 'manage';
        if (str_starts_with($permissionName, 'approve-')) return 'approve';
        if (str_starts_with($permissionName, 'reject-')) return 'reject';
        if (str_starts_with($permissionName, 'deliver-')) return 'deliver';
        if (str_starts_with($permissionName, 'apply-')) return 'apply';
        return 'other';
    }
}
