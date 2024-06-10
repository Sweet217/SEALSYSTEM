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


class ImagenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $multimedia = Multimedia::where('tipo', 'imagen')->first(); 
        
        $imagePath = 'resources/js/images/kioskogobierno.jpg';
        if (file_exists($imagePath)) {
            $imageName = 'kioskogobierno.jpg';

            try {
                $storedPath = Storage::disk('public')->put('images/pruebas/' . $imageName, file_get_contents($imagePath));
                
                Imagenes::create([
                  'nombre_archivo' => $imageName,
                  'tiempo' => 10,
                  'data' => 'images/pruebas/' . $imageName,
                  'multimedia_id' => $multimedia->multimedia_id,
                ]);
              } catch (Exception $e) {
                echo "Error: Image processing failed! " . $e->getMessage();
              }
            
        } else {
            echo "Error: Image file not found!";
        }
    }
}