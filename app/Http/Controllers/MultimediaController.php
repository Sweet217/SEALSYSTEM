<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\multimedia;
use App\Models\listas;
use Inertia\Inertia;
use App\Models\videos;
use App\Models\enlaces;
use App\Models\imagenes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MultimediaController extends Controller
{
    public function showMultimedia(Request $request, $id_lista)
    {
        $lista = Listas::find($id_lista);

        if (!$lista) {
            return response()->json(['error' => 'Invalid list ID'], 404);
        }

        $multimediaItems = $lista->multimedia()->with(['video', 'imagen', 'enlace'])->orderBy('posicion')->get();

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
            'multimediaData' => $multimediaItems
        ]);
    }
    public function crearMultimedia(Request $request) 
    {
        // Validar y crear la multimedia en la base de datos
        // Retorna el ID de la multimedia creada
        // Validar los datos recibidos del formulario
        $data = $request->validate([
            'tipo' => 'required',
            'id_lista' => 'required|exists:listas,id_lista'
        ]);

        // Crear la multimedia
        $multimedia = Multimedia::create([
            'tipo' => $data['tipo'],
            'id_lista' => $data['id_lista']
        ]);

        return response()->json(['message' => 'Multimedia creada exitosamente', 'multimedia' => $multimedia], 201);
    }

    public function actualizarOrden(Request $request)
    {
        $data = $request->validate([
            'positions' => 'required|array',
            'positions.*.multimedia_id' => 'required|integer',
            'positions.*.nueva_posicion' => 'required|integer',
        ]);

        try {
            DB::transaction(function () use ($data) {
                // Obtener todos los multimedia_id de las posiciones recibidas
                $multimediaIds = collect($data['positions'])->pluck('multimedia_id')->toArray();
    
                // Establecer temporalmente todas las posiciones a null
                Multimedia::whereIn('multimedia_id', $multimediaIds)->update(['posicion' => null]);
    
                // Actualizar las nuevas posiciones de acuerdo al orden recibido
                foreach ($data['positions'] as $position) {
                    $multimedia_id = $position['multimedia_id'];
                    $nueva_posicion = $position['nueva_posicion'];
    
                    // Actualizar la posición del elemento multimedia
                    Multimedia::where('multimedia_id', $multimedia_id)
                        ->update(['posicion' => $nueva_posicion]);
                }
            });
    
            return response()->json(['message' => 'Posiciones actualizadas correctamente'], 200);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['error' => 'Error al actualizar las posiciones'], 500);
        }

    }
}