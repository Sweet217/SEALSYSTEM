<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\imagenes;
use App\Models\multimedia;

use Illuminate\Support\Facades\Storage; 


class ImagenController extends Controller
{
    public function editarImagen(Request $request, $imagen_id) {
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
    
    public function crearImagen(Request $request) {
        $request->validate([
            'nombre_archivo' => 'required|string|max:255',
            'tiempo' => 'required|integer',
            'archivo' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);

        $file = $request->file('archivo');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = Storage::disk('public')->put('images/pruebas/' . $filename, file_get_contents($file));

        $multimedia = Multimedia::create([
            'tipo' => 'imagen',
            'id_lista' => $request->id_lista,
        ]);

        imagenes::create([
            'nombre_archivo' => $filename,
            'data' => 'images/pruebas/' . $filename,
            'multimedia_id' => $multimedia->multimedia_id,
            'tiempo' => $request->tiempo,
        ]);

        return response()->json(['message' => 'Imagen creada correctamente'], 201);
    }
}