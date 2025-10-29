<?php

namespace App\Livewire\Tables;

use App\Models\TipoInsumo;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ConfiguracionPermisosTable extends Component
{
    public $roles = [];
    public $tiposInsumo = [];
    public $permisos = [];

    public function mount()
    {
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $this->roles = Role::whereIn('name', ['jefe-departamento', 'auxiliar'])->get();
        $this->tiposInsumo = TipoInsumo::all();
        
        // Cargar permisos actuales
        $this->permisos = [];
        foreach ($this->roles as $role) {
            $this->permisos[$role->id] = [];
            foreach ($this->tiposInsumo as $tipo) {
                $permisoName = "solicitar-{$tipo->nombre_tipo}";
                $this->permisos[$role->id][$tipo->id] = $role->hasPermissionTo($permisoName);
            }
        }
    }

    public function togglePermiso($roleId, $tipoId)
    {
        $role = $this->roles->find($roleId);
        $tipo = $this->tiposInsumo->find($tipoId);
        
        if (!$role || !$tipo) {
            return;
        }

        $permisoName = "solicitar-{$tipo->nombre_tipo}";
        
        // Crear el permiso si no existe
        $permiso = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permisoName]);
        
        if ($this->permisos[$roleId][$tipoId]) {
            // Remover permiso
            $role->revokePermissionTo($permiso);
            $this->permisos[$roleId][$tipoId] = false;
        } else {
            // Agregar permiso
            $role->givePermissionTo($permiso);
            $this->permisos[$roleId][$tipoId] = true;
        }

        session()->flash('success', "Permiso actualizado para {$role->name} - {$tipo->nombre_tipo}");
    }

    public function render()
    {
        return view('livewire.tables.configuracion-permisos-table');
    }
}
