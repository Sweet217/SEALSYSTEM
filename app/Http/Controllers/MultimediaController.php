<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\multimedia;

class MultimediaController extends Controller
{
    public function showMultimedia() {
        $mediaType = $request->get('tipo'); // Get the requested media type

            // Validate the media type against allowed values and existing types in the 'multimedia' table
            $allowedMediaTypes = ['video', 'imagen', 'enlace_youtube'];
            if (!in_array($mediaType, $allowedMediaTypes)) {
                return response()->json(['error' => 'Invalid media type'], 400);
            }

            //Valida que 'tipo' lo este sacando de multimedia
            $validTypes = Multimedia::select('tipo')->distinct()->pluck('tipo')->toArray();
            if (!in_array($mediaType, $validTypes)) {
                return response()->json(['error' => 'Invalid media type: Not found in multimedia table'], 400);
            }

            $data = [];
            switch ($mediaType) {
            case 'video':
                $data['videos'] = Video::all(); // Fetch all videos
                break;
            case 'imagen':
                $data['imagenes'] = Imagen::all(); // Fetch all images
                break;
            case 'enlace_youtube':
                $data['enlace_youtube'] = Enlace::all(); // Fetch all links
                break;
            default:
                // Handle invalid or missing media type
            }

            return Inertia::render('mainpage', $data);
    }
}