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

    public function doesTheDeviceHasALicense(Request $request, $mac)
    {
        $equipo = Equipos::where('mac', $mac)->first();

        if (!$equipo) {
            return response()->json(['error' => 'Equipo not found'], 404);
        }

        // Check if the equipo has at least one license
        $licencia = Licencias::where('equipo_id', $equipo->equipo_id)->first();

        if (!$licencia) {
            return response()->json(['has_license' => false, 'license_number' => null]);
        }

        // Format the license dates
        $licencia_inicio = Carbon::parse($licencia->licencia_inicio)->format('d/m/Y');
        $licencia_final = Carbon::parse($licencia->licencia_final)->format('d/m/Y');
        $formatted_dates = "$licencia_inicio al $licencia_final";

        return response()->json([
            'has_license' => true,
            'license_number' => $licencia->licencia,
            'license_dates' => $formatted_dates
        ]);
    }
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

        // If a specific lista_id is provided, filter by it
        if ($request->has('lista_id')) {
            $lista_ids = [$request->input('lista_id')];
        }

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

    public function getListasByMac($mac)
    {
        // Find the equipo with the given MAC address
        $equipo = equipos::where('mac', $mac)->first();

        if (!$equipo) {
            return response()->json(['error' => 'Equipo not found'], 404);
        }

        // Get the listas associated with the equipo
        $listas = listas::where('equipo_id', $equipo->equipo_id)->get(['id_lista', 'nombre']);

        if ($listas->isEmpty()) {
            return response()->json(['error' => 'No listas found for this equipo'], 404);
        }

        return response()->json($listas);
    }

    public function addLicense(Request $request)
    {
        // Validar los parámetros de la solicitud
        $request->validate([
            'mac' => 'required|string',
            'licencia' => 'required|string',
        ]);

        $mac = $request->input('mac');
        $licenciaNumero = $request->input('licencia');

        // Encontrar el equipo por la dirección MAC
        $equipo = Equipos::where('mac', $mac)->first();

        if (!$equipo) {
            return response()->json(['message' => 'Equipo no encontrado'], 404);
        }

        // Encontrar la licencia por el número de licencia
        $licencia = Licencias::where('licencia', $licenciaNumero)->first();

        if (!$licencia) {
            return response()->json(['message' => 'Licencia no encontrada'], 404);
        }

        // // Eliminar las licencias anteriores asociadas al equipo
        // Licencias::where('equipo_id', $equipo->equipo_id)->delete();

        // Asignar el equipo_id a la nueva licencia
        $licencia->equipo_id = $equipo->equipo_id;

        // Determinar fechas de inicio y fin basadas en el periodo seleccionado
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

        // Guardar los cambios en la base de datos para la licencia
        $licencia->save();

        // Actualizar el equipo con el nuevo licencia_id
        $equipo->licencia_id = $licencia->licencia_id;
        $equipo->save();

        return response()->json(['message' => 'Licencia asignada correctamente'], 200);
    }
}
