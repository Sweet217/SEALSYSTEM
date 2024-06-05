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
        // //Crear permisos
        // Permission::create(['name' => 'Registrar usuarios']);

        // // Crear roles y asignar permisos Administrador
        // $adminstratorRole = Role::create(['name' => 'Administrador']);
        // $administratorRole->givePermissionTo('Registrar usuarios');
        // $administratorRole->givePermissionTo('Acceder a la seccion de registro');

        // // Crear roles y asignar permisos Operador
        
        // $operadorRole = Role::create(['name' => 'Operador']);
        // $operadorRole->givePermissionTo('');
        
    }
}