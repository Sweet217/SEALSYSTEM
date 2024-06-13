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

        Enlace::create([
            'data' => $request->data,
            'multimedia_id' => $multimedia->id,
        ]);

        return response()->json(['message' => 'Enlace creado correctamente'], 201);
    }
}