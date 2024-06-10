<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\imagenes;

class ImagenController extends Controller
{
    public function editarImagen(Request $request) {
        $imagen = Imagen::findOrFail($id);
        $imagen->duracion = $request->input('duracion');
        $imagen->save();

        return response()->json(['message' => 'Duración de imagen actualizada correctamente'], 200);
    }
}