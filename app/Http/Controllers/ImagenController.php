<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\imagenes; //importa el modelo imagenes
use App\Models\multimedia; //importa el modelo multimedia

use Illuminate\Support\Facades\Storage;


class ImagenController extends Controller
{
    public function editarImagen(Request $request, $imagen_id)
    {
        $request->validate([
            'tiempo' => 'integer', // Asegúrate de ajustar la validación según tus necesidades
        ]);

        try {
            // Buscar la imagen por su ID
            $imagen = imagenes::findOrFail($imagen_id);

            // Actualizar la duración de la imagen
            $imagen->tiempo = $request->tiempo;
            $imagen->save();

            // Respondemos con un mensaje de éxito
            return response()->json(['message' => 'La duración de la imagen se actualizó correctamente'], 200);
        } catch (\Exception $e) {
            // Manejar cualquier error que ocurra
            return response()->json(['message' => 'Error al actualizar la duración de la imagen'], 500);
        }
    }

    /*  Crear una nueva imagen mediante la creacion de una multimedia y enlazarlos.
     */

    public function crearImagen(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombre_archivo' => 'required|string|max:255',
            'tiempo' => 'required|integer',
            'archivo' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);

        // Obtener el archivo cargado
        $file = $request->file('archivo');
        $filename = time() . '.' . $file->getClientOriginalExtension();

        // Almacenar el archivo en el almacenamiento público
        $path = Storage::disk('public')->put('/images/pruebas/' . $filename, file_get_contents($file));

        // Crear un nuevo registro de multimedia de tipo 'imagen'
        $multimedia = Multimedia::create([
            'tipo' => 'imagen',
            'id_lista' => $request->id_lista,
        ]);

        // Crear el registro de la imagen asociada
        Imagenes::create([
            'nombre_archivo' => $filename,
            'data' => 'images/pruebas/' . $filename,
            'multimedia_id' => $multimedia->multimedia_id,
            'tiempo' => $request->tiempo,
        ]);

        // Respuesta exitosa
        return response()->json(['message' => 'Imagen creada correctamente'], 201);
    }

    /**
     * Elimina una imagen y su correspondiente registro en Multimedia.
     *
     */
    public function eliminarImagen(Request $request, $multimedia_id, $imagen_id)
    {
        // Buscar la imagen por su ID
        $imagen = Imagenes::find($imagen_id);

        // Verificar si la imagen existe y si pertenece al multimedia indicado
        if (!$imagen || $imagen->multimedia_id != $multimedia_id) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }

        // Eliminar el archivo físico de la imagen si existe en el almacenamiento
        if (Storage::disk('public')->exists($imagen->data)) {
            Storage::disk('public')->delete($imagen->data);
        }

        // Eliminar el registro de la imagen
        $imagen->delete();

        // Buscar y eliminar el registro de Multimedia asociado, si existe
        $multimedia = Multimedia::find($multimedia_id);
        if ($multimedia) {
            $multimedia->delete();
        }

        // Respuesta exitosa
        return response()->json(['message' => 'Imagen eliminada correctamente'], 200);
    }
}