<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Users;
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
        $user1 = Users::first(); // Assuming UsuarioSeeder runs first
        $licencia1 = Licencias::first();

        if ($licencia1) {
            equipos::create([
                'nombre' => 'Kiosko Sonora',
                'licencia_id' => $licencia1->licencia_id,
                'mac' => NULL,
                'server_key' => NULL,
                'user_id' => $user1->user_id,
            ]);
        } else {
            equipos::create([
                'nombre' => 'Kiosko Sonora',
                'mac' => NULL,
                'server_key' => NULL,
                'user_id' => $user1->user_id,
            ]);

        }
    }
}