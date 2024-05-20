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

class LicenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipo1 = equipos::first(); // Assuming EquipoSeeder runs first

        licencias::create([
            'licencia' => '12403024',
            'licencia_inicio' => now(),
            'licencia_final' => now()->addMonth(),
            'equipo_id' => $equipo1->equipo_id,
        ]);
    }
}