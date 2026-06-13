<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // limpiar cache antes de empezar
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // permisos que vamos a usar en el sistema
        $permisos = [
            'ver-usuarios',
            'crear-usuarios',
            'editar-usuarios',
            'eliminar-usuarios',
            'ver-roles',
            'asignar-roles',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate([
                'name' => $permiso,
                'guard_name' => 'api'
            ]);
        }

        // rol admin tiene todos los permisos
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $admin->givePermissionTo(Permission::all());

        // medico solo puede ver
        $medico = Role::firstOrCreate(['name' => 'medico', 'guard_name' => 'api']);
        $medico->givePermissionTo(['ver-usuarios']);

        // enfermero solo puede ver
        $enfermero = Role::firstOrCreate(['name' => 'enfermero', 'guard_name' => 'api']);
        $enfermero->givePermissionTo(['ver-usuarios']);

        // laboratorio solo puede ver
        $laboratorio = Role::firstOrCreate(['name' => 'laboratorio', 'guard_name' => 'api']);
        $laboratorio->givePermissionTo(['ver-usuarios']);
    }
}
