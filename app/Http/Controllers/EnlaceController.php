<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\enlaces;
use App\Models\multimedia;
use Inertia\Inertia;

class EnlaceController extends Controller
{
    public function crearEnlace(Request $request) {
        $request->validate([
            'data' => 'required|string',
        ]);

        $multimedia = Multimedia::create([
            'tipo' => 'enlace',
            'id_lista' => $request->id_lista,
        ]);

        enlaces::create([
            'data' => $request->data,
            'multimedia_id' => $multimedia->multimedia_id,
        ]);

        return response()->json(['message' => 'Enlace creado correctamente'], 201);
    }

    public function eliminarEnlace(Request $request, $multimedia_id, $enlace_id) {
        $enlace = Enlaces::find($enlace_id);
        
        if (!$enlace || $enlace->multimedia_id != $multimedia_id) {
            return response()->json(['message' => 'Enlace no encontrado'], 404);
        }

        $enlace->delete();

        $multimedia = Multimedia::find($multimedia_id);
        if ($multimedia) {
            $multimedia->delete();
        }

        return response()->json(['message' => 'Enlace eliminado correctamente'], 200);
        
    }
}