<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\equipos; //Importa el modelo equipos
use App\Models\Users;   //Importa el modelo users
use App\Models\licencias; //Importa el modelo licencias
use App\Models\videos; //Importar el modelo de videos
use App\Models\imagenes; //Importar el modelo de imagenes
use App\Models\enlaces; //Importar el modelo de enlaces
use App\Models\listas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class LocalPythonWsController extends Controller
{
    public function getDataByMac(Request $request, $mac)
    {
        // Find the equipo with the given encrypted MAC address
        $equipo = equipos::where('mac', $mac)->first();

        if (!$equipo) {
            return response()->json(['error' => 'Equipo not found'], 404);
        }

        // Get the listas associated with the equipo
        $listas = listas::where('equipo_id', $equipo->id)->pluck('id');

        if ($listas->isEmpty()) {
            return response()->json(['error' => 'No listas found for this equipo'], 404);
        }

        // Retrieve media data associated with the listas
        $videos = videos::whereIn('id_lista', $listas)
            ->join('multimedia', 'videos.multimedia_id', '=', 'multimedia.multimedia_id')
            ->orderBy('posicion')
            ->get(['data', 'posicion']);

        $imagenes = imagenes::whereIn('id_lista', $listas)
            ->join('multimedia', 'imagenes.multimedia_id', '=', 'multimedia.multimedia_id')
            ->orderBy('posicion')
            ->get(['data', 'tiempo', 'posicion']);

        $enlaces = enlaces::whereIn('id_lista', $listas)
            ->join('multimedia', 'enlaces.multimedia_id', '=', 'multimedia.multimedia_id')
            ->orderBy('posicion')
            ->get(['data', 'posicion']);

        // Merge and sort media data by 'posicion'
        $mediaData = collect();

        foreach ($videos as $video) {
            $mediaData->push([
                'data' => $video->data,
                'tiempo' => 0,
                'posicion' => $video->posicion
            ]);
        }

        foreach ($imagenes as $imagen) {
            $mediaData->push([
                'data' => $imagen->data,
                'tiempo' => $imagen->tiempo,
                'posicion' => $imagen->posicion
            ]);
        }

        foreach ($enlaces as $enlace) {
            $mediaData->push([
                'data' => $enlace->data,
                'tiempo' => 0,
                'posicion' => $enlace->posicion
            ]);
        }

        $mediaData = $mediaData->sortBy('posicion')->values()->all();

        return response()->json($mediaData);
    }
}
