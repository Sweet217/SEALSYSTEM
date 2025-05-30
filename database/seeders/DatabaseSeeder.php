<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Users;
use App\Models\equipos;
use App\Models\licencias;
use App\Models\listas;
use App\Models\multimedia;
use App\Models\videos;
use App\Models\imagenes;
use App\Models\enlace_youtube;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsuarioSeeder::class,

            // EquipoSeeder::class,
            // LicenciaSeeder::class,
            // ListaSeeder::class,
            // MultimediaSeeder::class,
            // ImagenSeeder::class,
            // EnlaceSeeder::class,
            // VideoSeeder::class
        ]);
    }
}