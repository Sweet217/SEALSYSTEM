<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\videos;
use App\Models\multimedia;
use Inertia\Inertia;

use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Almacena un nuevo video en el sistema.
     *
     * @param Request $request Objeto de solicitud HTTP que contiene los datos del video.
     * @return \Illuminate\Http\Response Respuesta JSON con un mensaje de éxito o error.
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación de los datos del video falla.
     * 
     */
    public function crearVideo(Request $request)
    {
        $request->validate([
            'nombre_archivo' => 'required|string|max:255',
            'archivo' => 'required|file|mimes:mp4', // Tamaño máximo de archivos en kilobytes (ajustar si es necesario)
        ]);

        $file = $request->file('archivo');
        $filename = $request->nombre_archivo;

        try {
            // Almacenar el archivo de video
            $path = Storage::disk('public')->put('videos/pruebas/' . $filename, file_get_contents($file));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al almacenar el video'], 500);
        }

        // Crear registro en la tabla 'multimedia'
        $multimedia = Multimedia::create([
            'tipo' => 'video',
            'id_lista' => $request->id_lista,
        ]);

        // Crear registro en la tabla 'videos'
        Videos::create([
            'nombre_archivo' => $filename,
            'data' => 'videos/pruebas/' . $filename,
            'multimedia_id' => $multimedia->multimedia_id,
        ]);

        return response()->json(['message' => 'Video creado correctamente'], 201);
    }

    /**
     * Elimina un video del sistema.
     *
     * @param Request $request Objeto de solicitud HTTP (puede no usarse en esta función).
     * @param int $multimedia_id Identificador del registro en la tabla 'multimedia' asociado al video.
     * @param int $video_id Identificador del registro en la tabla 'videos' asociado al video.
     * @return \Illuminate\Http\Response Respuesta JSON con un mensaje de éxito o error.
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el video o el registro asociado en 'multimedia'.
     */
    public function eliminarVideo(Request $request, $multimedia_id, $video_id)
    {
        $video = Videos::find($video_id);

        if (!$video || $video->multimedia_id != $multimedia_id) {
            return response()->json(['message' => 'Video no encontrado'], 404);
        }

        if (Storage::disk('public')->exists($video->data)) {
            Storage::disk('public')->delete($video->data);
        }

        $video->delete();

        $multimedia = Multimedia::find($multimedia_id);
        if ($multimedia) {
            $multimedia->delete();
        }

        return response()->json(['message' => 'Video eliminado correctamente'], 200);
    }
}