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
        // Find the equipo with the given MAC address
        $equipo = equipos::where('mac', $mac)->first();

        if (!$equipo) {
            return response()->json(['error' => 'Equipo not found'], 404);
        }

        // Get the listas associated with the equipo
        $listas = listas::where('equipo_id', $equipo->equipo_id)->pluck('id_lista');

        if ($listas->isEmpty()) {
            return response()->json(['error' => 'No listas found for this equipo'], 404);
        }

        $lista_ids = $listas->toArray();

        // Retrieve multimedia data
        $videos = videos::whereIn('id_lista', $lista_ids)
            ->join('multimedia', 'videos.multimedia_id', '=', 'multimedia.multimedia_id')
            ->orderBy('posicion')
            ->get(['data', 'posicion']);

        $imagenes = imagenes::whereIn('id_lista', $lista_ids)
            ->join('multimedia', 'imagenes.multimedia_id', '=', 'multimedia.multimedia_id')
            ->orderBy('posicion')
            ->get(['data', 'tiempo', 'posicion']);

        $enlaces = enlaces::whereIn('id_lista', $lista_ids)
            ->join('multimedia', 'enlaces.multimedia_id', '=', 'multimedia.multimedia_id')
            ->orderBy('posicion')
            ->get(['data', 'posicion']);

        // Merge and format media data
        $mediaData = collect();

        foreach ($videos as $video) {
            $mediaData->push([
                'data' => url("storage/" . $video->data), // Encode URL
                'tiempo' => 0,
                'posicion' => $video->posicion
            ]);
        }

        foreach ($imagenes as $imagen) {
            $mediaData->push([
                'data' => url("storage/" . $imagen->data), // Encode URL
                'tiempo' => $imagen->tiempo,
                'posicion' => $imagen->posicion
            ]);
        }

        foreach ($enlaces as $enlace) {
            $mediaData->push([
                'data' => $enlace->data, // Assume URLs in enlaces are already well-formatted
                'tiempo' => 0,
                'posicion' => $enlace->posicion
            ]);
        }

        // Sort media data by 'posicion'
        $mediaData = $mediaData->sortBy('posicion')->values()->all();

        return response()->json($mediaData);
    }

    public function addLicense(Request $request)
    {
        // Validate the request parameters
        $request->validate([
            'mac' => 'required|string',
            'licencia' => 'required|string',
        ]);

        $mac = $request->input('mac');
        $licencia = $request->input('licencia');

        // Find the team by MAC address
        $equipo = Equipos::where('mac', $mac)->first();

        if (!$equipo) {
            return response()->json(['message' => 'Equipo no encontrado'], 404);
        }

        // Find the license by license number
        $licencia = Licencias::where('licencia', $licencia)->first();

        if (!$licencia) {
            return response()->json(['message' => 'Licencia no encontrada'], 404);
        }

        // Check if the team is already associated with another license
        $licenciaExistente = Licencias::where('equipo_id', $equipo->equipo_id)->first();
        if ($licenciaExistente && $licenciaExistente->licencia_id != $licencia->licencia_id) {
            // If the team already has an associated license, disassociate it
            $licenciaExistente->equipo_id = null;
            $licenciaExistente->save();
        }

        // Check if the team_id of the license and the team being edited are the same
        if ($licencia->equipo_id != $equipo->equipo_id) {
            return response()->json(['message' => 'Licencia no válida para este dispositivo'], 400);
        }

        // Assign the team_id to the license
        $licencia->equipo_id = $equipo->equipo_id;

        // Determine start and end dates based on the selected period
        if (is_null($licencia->licencia_inicio) && is_null($licencia->licencia_final)) {
            $licencia_inicio = Carbon::now();
            $licencia_final = match ($licencia->periodo) {
                'PRUEBA' => $licencia_inicio->copy()->addDays(7),
                'MENSUAL' => $licencia_inicio->copy()->addMonth(),
                'TRIMESTRAL' => $licencia_inicio->copy()->addMonths(3),
                'SEMESTRAL' => $licencia_inicio->copy()->addMonths(6),
                'ANUAL' => $licencia_inicio->copy()->addYear(),
                default => null,
            };

            $licencia->licencia_inicio = $licencia_inicio;
            $licencia->licencia_final = $licencia_final;
        }

        // Save changes to the database
        $licencia->save();

        return response()->json(['message' => 'Licencia asignada correctamente'], 200);
    }
}
