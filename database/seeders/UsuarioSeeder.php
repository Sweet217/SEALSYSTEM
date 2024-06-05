<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Users;
use App\Models\equipos;
use App\Models\licencias;
use App\Models\listas;
use App\Models\multimedia;
use App\Models\videos;
use App\Models\imagenes;
use App\Models\enlace_youtube;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Users::create([
            'nombre' => 'Juan Hernandez',
            'email' => 'JuanHernandez@solytec.com',
            'password' => Hash::make('password'),
            'estado' => 'Activo',
            'telefono' => '1234567890',
            'tipo_usuario' => 'Administrador',
        ]);

        Users::create([
            'nombre' => 'Gabriel Velazquez',
            'email' => 'GabrielVelazquez@solytec.com',
            'password' => Hash::make('password'),
            'estado' => 'Activo',
            'telefono' => '312 595 3394',
            'tipo_usuario' => 'Operador',
        ]);
    }
}