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
        $multimedia1 = Multimedia::first(); // Assuming ListaSeeder runs first

        $videoPath = 'resources/js/videos/396798656_1692946187885088_8328249334472428609_n.mp4'; // Adjust the path if needed
        
        if (file_exists($videoPath)) {
            $videoName = 'kioskogobiernovideo.mp4'; // Adjust the filename if needed

            try {
                $videoContent = file_get_contents($videoPath);
                Storage::disk('public')->put('videos/pruebas/' . $videoName, $videoContent);
                // Create Imagenes record (replace with your logic)
                Videos::create([
                  'nombre_archivo' => $videoName,
                  'data' => $videoPath,
                  'multimedia_id' => $multimedia1->multimedia_id,
                ]);
              } catch (Exception $e) {
                echo "Error: Image processing failed! " . $e->getMessage();
              }
            
        } else {
        echo "Error: Video file not found!";
        }
    }
}