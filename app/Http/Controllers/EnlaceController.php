<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\enlaces; // Importar modelo Enlaces
use App\Models\multimedia; // Importar modelo multimedia
use Inertia\Inertia;

class EnlaceController extends Controller
{
    /**
     * Crea un nuevo enlace multimedia y lo asocia a una lista.
     */
    
    public function crearEnlace(Request $request) {
        $request->validate([
            'data' => 'required|string',
        ]);

         // Crear un nuevo registro de multimedia de tipo 'enlace'

        $multimedia = Multimedia::create([
            'tipo' => 'enlace',
            'id_lista' => $request->id_lista,
        ]);
        // Crear el enlace en la tabla Enlaces asociándolo al multimedia creado
        enlaces::create([
            'data' => $request->data,
            'multimedia_id' => $multimedia->multimedia_id,
        ]);

        return response()->json(['message' => 'Enlace creado correctamente'], 201);
    }

    /** 
     *  Elimina un enlace multimedia y su correspondiente registro en Multimedia.
    */

    public function eliminarEnlace(Request $request, $multimedia_id, $enlace_id) {
        // Buscar el enlace por su ID
        $enlace = Enlaces::find($enlace_id);
        // Verificar si el enlace existe y si pertenece al multimedia indicado
        if (!$enlace || $enlace->multimedia_id != $multimedia_id) {
            return response()->json(['message' => 'Enlace no encontrado'], 404);
        }
        // Eliminar el enlace
        $enlace->delete();
        // Buscar y eliminar el registro de Multimedia asociado, si existe
        $multimedia = Multimedia::find($multimedia_id);
        if ($multimedia) {
            $multimedia->delete();
        }

        return response()->json(['message' => 'Enlace eliminado correctamente'], 200);
        
    }
}