<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\usuarios;
use App\Models\equipos;
use App\Models\licencias;
use App\Models\listas;
use App\Models\multimedia;
use App\Models\videos;
use App\Models\imagenes;
use App\Models\enlace_youtube;

class EquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     
    public function run(): void
    {
        $user1 = usuarios::first(); // Assuming UsuarioSeeder runs first
        
        equipos::create([
            'usuario_id' => $user1->usuario_id,
        ]);
    }
}