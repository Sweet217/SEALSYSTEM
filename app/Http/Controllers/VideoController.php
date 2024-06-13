<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\videos;
use App\Models\multimedia;
use Inertia\Inertia;

use Illuminate\Support\Facades\Storage; 

class VideoController extends Controller
{
    public function crearVideo(Request $request) {
        $request->validate([
            'nombre_archivo' => 'required|string|max:255',
            'archivo' => 'required|file|mimes:mp4|max:20480',
        ]);

        $file = $request->file('archivo');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = Storage::disk('public')->put('videos/pruebas/' . $filename, file_get_contents($file));

        $multimedia = Multimedia::create([
            'tipo' => 'video',
            'id_lista' => $request->id_lista,
        ]);

        Video::create([
            'nombre_archivo' => $filename,
            'data' => 'videos/pruebas/' . $filename,
            'multimedia_id' => $multimedia->id,
        ]);

        return response()->json(['message' => 'Video creado correctamente'], 201);
        
    }
}