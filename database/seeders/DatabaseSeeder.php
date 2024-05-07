<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use App\Models\Usuario;
use App\Models\Equipo;
use App\Models\Licencia;
use App\Models\ListaReproduccion;
use App\Models\Multimedia;
use App\Models\Video;
use App\Models\Imagen;
use App\Models\EnlaceYoutube;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = FakerFactory::create(); //Para crear datos falsos para PRUEBAS, que es lo que busco con este seeder para empezar el desarollo. 

        // Create a user
        $usuario = Usuario::create([
            'nombre' => $faker->name,
            'correo' => $faker->unique()->safeEmail,
            'contrasena' => bcrypt('password'),
            'estado' => 'Activo',
            'telefono' => $faker->phoneNumber,
            'tipo_usuario' => 'Usuario',
        ]);

        // Create an equipo
        $equipo = Equipo::create([
            'licencia' => $faker->unique()->word,
            'usuario_id' => $usuario->id,
        ]);

        // Create a licencia
        $licencia = Licencia::create([
            'licencia' => $faker->unique()->word,
            'licencia_inicio' => now(),
            'licencia_final' => now()->addMonth(),
            'equipo_id' => $equipo->id,
        ]);

        // Create a playlist
        $listaReproduccion = ListaReproduccion::create([
            'nombre' => $faker->sentence,
            'equipo_id' => $equipo->id,
        ]);

        // Create a multimedia item
        $multimedia = Multimedia::create([
            'tipo' => 'Video',
            'id_lista_reproduccion' => $listaReproduccion->id,
        ]);

        // Create a video
        $video = Video::create([
            'nombre_archivo' => $faker->unique()->fileName,
            'data' => base64_encode(file_get_contents($faker->image())),
            'multimedia_id' => $multimedia->id,
        ]);

        // Create an image
        $imagen = Imagen::create([
            'nombre_archivo' => $faker->unique()->fileName,
            'tiempo' => $faker->numberBetween(1, 10),
            'data' => base64_encode(file_get_contents($faker->image())),
            'multimedia_id' => $multimedia->id,
        ]);

        // Create a YouTube link
        $enlaceYoutube = EnlaceYoutube::create([
            'data' => 'https://www.youtube.com/watch?v=' . $faker->unique()->youtubeId,
            'multimedia_id' => $multimedia->id,
        ]);
    }
}