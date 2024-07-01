<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage; 

use App\Models\Users;
use App\Models\equipos;
use App\Models\licencias;
use App\Models\listas;
use App\Models\multimedia;
use App\Models\videos;
use App\Models\imagenes;
use App\Models\enlace_youtube;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $multimedia = Multimedia::where('tipo', 'video')->first();

        $videoPath = 'resources/js/videos/396798656_1692946187885088_8328249334472428609_n.mp4';
        if (file_exists($videoPath)) {
            $videoName = 'kioskogobierno.mp4';

            try {
                $storedPath = Storage::disk('public')->put('videos/pruebas/' . $videoName, file_get_contents($videoPath));
                
                Videos::create([
                    'nombre_archivo' => $videoName,
                    'data' => 'videos/pruebas/' . $videoName,
                    'multimedia_id' => $multimedia->multimedia_id,
                ]);
              } catch (Exception $e) {
                echo "Error: Video processing failed! " . $e->getMessage();
              }
            
        } else {
            echo "Error: Video file not found!";
        }
    }
}