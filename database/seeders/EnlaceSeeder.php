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
use App\Models\enlaces;

class EnlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $multimedia = Multimedia::where('tipo', 'enlace')->first();

        enlaces::create([
            'data' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw', 
            'multimedia_id' => $multimedia->multimedia_id,
        ]);
        
    }
}