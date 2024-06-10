<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\multimedia;
use App\Models\listas;
use Inertia\Inertia;

class MultimediaController extends Controller
{
    public function showMultimedia(Request $request, $id_lista)
    {
        $lista = Listas::find($id_lista);

        if (!$lista) {
            return response()->json(['error' => 'Invalid list ID'], 404);
        }

        $multimediaItems = $lista->multimedia()->with(['video', 'imagen', 'enlace'])->get();

        $multimediaData = $multimediaItems->map(function($item) {
            if ($item->tipo == 'video' && $item->video) {
                return [
                    'tipo' => 'video',
                    'data' => $item->video,
                ];
            } elseif ($item->tipo == 'imagen' && $item->imagen) {
                return [
                    'tipo' => 'imagen',
                    'data' => $item->imagen,
                ];
            } elseif ($item->tipo == 'enlace' && $item->enlace) {
                return [
                    'tipo' => 'enlace',
                    'data' => $item->enlace,
                ];
            }
            return null;
        })->filter();

        return Inertia::render('Multimedia', [
            'listaData' => $lista,
            'multimedia' => $multimediaData,
        ]);
    }
}