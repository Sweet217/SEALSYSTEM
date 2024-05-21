<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Crear permisos
        Permission::create(['name' => '']);

        // Crear roles y asignar permisos Administrador

        $role = Role::create(['name' => 'Administrador']);
        $role->givePermissionTo('');

        
        
        // Crear roles y asignar permisos Operador
        
        $role = Role::create(['name' => 'Operador']);
        $role->givePermissionTo('');
        
    }
}