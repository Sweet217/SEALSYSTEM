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

class MultimediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lista1 = listas::first(); // Assuming ListaSeeder runs first
        
        multimedia::create([
            'tipo' => 'video',
            'id_lista' => $lista1->id_lista,
        ]);
    }
}