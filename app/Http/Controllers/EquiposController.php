<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\equipos; //Importa el modelo equipos
use App\Models\Users;   //Importa el modelo users
use App\Models\licencias; //Importa el modelo licencias
use Carbon\Carbon;

use Illuminate\Support\Facades\Crypt;

class EquiposController extends Controller
{
    /**
     * Display a listing of the equipos.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     */
    public function showEquipos(Request $request)
    {
        // Validar que user_id esté presente en la solicitud
        $request->validate([
            'user_id' => 'required'
        ]);

        // Obtener el user_id de la solicitud
        $userId = $request->input('user_id');

        // Obtener solo los equipos que pertenecen al usuario especificado por user_id
        $data = Equipos::where('user_id', $userId)
            ->with('usuarios', 'licencias')
            ->get();

        // Renderizar la vista usando Inertia.js
        return Inertia::render('Equipos', [
            'equipos' => $data
        ]);
    }

    /**
     * Store a newly created equipo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function crearEquipos(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'numero_licencia' => 'nullable',
            'nombre_usuario' => 'required',
            'mac' => 'nullable'
        ]);

        // Buscar el ID del usuario por su nombre
        $usuario = Users::where('nombre', $request->input('nombre_usuario'))->first();
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Buscar el ID de la licencia por su número (puede ser null)
        $licencia = Licencias::where('licencia', $request->input('numero_licencia'))->first();
        $licencia_id = $licencia ? $licencia->licencia_id : null;

        if ($request->input('numero_licencia')) {

            if (!$licencia) {
                return response()->json(['message' => 'Licencia no valida'], 404);
            }

            // Verificar si la licencia ya está asociada a otro equipo
            if ($licencia->equipo_id) {
                return response()->json(['message' => 'Esta licencia ya está asociada a otro equipo'], 400);
            }

            // Asignar el ID de la licencia
            $licencia_id = $licencia->licencia_id;
        } else {
            $licencia_id = null;
        }

        // Crear el nuevo equipo
        $equipo = new Equipos();
        $equipo->nombre = $request->input('nombre');
        $equipo->licencia_id = $licencia_id;
        $equipo->user_id = $usuario->user_id;
        $equipo->mac = $request->input('mac');

        $equipo->save();

        // Actualizar la licencia si existe
        if ($licencia) {
            $licencia->equipo_id = $equipo->equipo_id;
            $licencia->save();
        }


        return response()->json(['message' => 'Equipo creado correctamente', 'equipo' => $equipo], 201);
    }


    /**
     * remueve el equipo de la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $equipo_id
     * @return \Illuminate\Http\Response
     */
    public function borrarEquipos(Request $request, $equipo_id)
    {
        $equipo = Equipos::find($equipo_id);

        if (!$equipo) {
            return response()->json(['message' => 'Equipo no encontrado'], 404);
        }

        $equipo->delete();

        return response()->json(['message' => 'Equipo eliminado correctamente'], 200);
    }

    /**
     * Update the specified equipo in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $equipo_id
     * @return \Illuminate\Http\Response
     */
    public function editarEquipos(Request $request, $equipo_id)
    {
        $request->validate([
            'nombre' => 'required',
            'numero_licencia' => 'nullable|string',
            'nombre_usuario' => 'required|string',
        ]);

        $equipo = Equipos::find($equipo_id);

        if (!$equipo) {
            return response()->json(['message' => 'Equipo no encontrado'], 404);
        }

        // Obtener el usuario por nombre
        $usuario = Users::where('nombre', $request->input('nombre_usuario'))->first();

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Obtener la licencia por numero de licencia
        $licencia = Licencias::where('licencia', $request->input('numero_licencia'))->first();

        // Verificar si la licencia es nula
        if ($request->input('numero_licencia') == '') {
            $licencia_id = null;
        } else if (!$licencia) {
            return response()->json(['message' => 'Licencia no encontrada'], 404);
        } else {
            // Verificar si la licencia ya está asociada a otro equipo
            if ($licencia->equipo_id && $licencia->equipo_id != $equipo_id) {
                return response()->json(['message' => 'Esta licencia ya está asociada a otro equipo'], 400);
            }

            // Verificar si el equipo ya está asociado con otra licencia
            $licenciaExistente = Licencias::where('equipo_id', $equipo_id)->first();
            if ($licenciaExistente && $licenciaExistente->licencia_id != $licencia->licencia_id) {
                // Si el equipo ya tiene una licencia asociada, desasociarla
                $licenciaExistente->equipo_id = null;
                $licenciaExistente->save();
            }

            // Asignar el ID de la licencia
            $licencia_id = $licencia->licencia_id;

            // Actualizar el campo equipo_id de la licencia anterior
            if ($equipo->licencia_id && $equipo->licencia_id != $licencia_id) {
                $licencia_anterior = Licencias::find($equipo->licencia_id);
                if ($licencia_anterior) {
                    $licencia_anterior->equipo_id = null;
                    $licencia_anterior->save();
                }
            }
        }

        // Actualizar los datos del equipo y la licencia
        $equipo->nombre = $request->input('nombre');
        $equipo->user_id = $usuario->user_id;
        $equipo->licencia_id = $licencia_id;

        if ($licencia) {
            $licencia->equipo_id = $equipo->equipo_id;
        }

        $licenciaEquipo = Licencias::where('equipo_id', $equipo_id)->first();
        if ($licenciaEquipo) {
            // Si el equipo tenía una licencia asociada, desasociarla
            $licenciaEquipo->equipo_id = null;
            $licenciaEquipo->save();
        }

        // Guardar los cambios en la base de datos
        $equipo->save();
        if ($licencia) {
            $licencia->save();
        }

        return response()->json(['message' => 'Equipo editado correctamente'], 200);
    }

    public function obtenerEquiposUsuario($userId)
    {
        try {
            // Encontrar los equipos asociados con el user_id
            $equipos = Equipos::where('user_id', $userId)->get();

            // Enviar la lista de equipos como respuesta
            return response()->json(['equipos' => $equipos], 200);

        } catch (\Exception $e) {
            \Log::error('Error al obtener los equipos del usuario: ' . $e->getMessage());
            return response()->json(['message' => 'Error al obtener los equipos del usuario'], 500);
        }
    }
    public function verEquiposUsuarioPagina($userId)
    {
        // Obtener los equipos del usuario basado en $userId
        $equipos = Equipos::where('user_id', $userId)
            ->with('usuarios', 'licencias')
            ->get();

        // Renderizar la vista utilizando Inertia.js
        return Inertia::render('Equipos', [
            'equipos' => $equipos
        ]);
    }

    public function agregarServerKey(Request $request, $equipo_id)
    {
        $validatedData = $request->validate([
            'server_key' => 'required|string',
            'licencia' => 'required|string',
            'mac' => 'required|string',
            'periodo' => 'required|string|in:PRUEBA,MENSUAL,TRIMESTRAL,SEMESTRAL,ANUAL'
        ]);

        try {
            // Encontrar el equipo por ID
            $equipo = Equipos::find($equipo_id);

            // Verificar si el equipo existe
            if (!$equipo) {
                return response()->json(['error' => 'Equipo no encontrado'], 404);
            }

            // Actualizar la server_key y la mac del equipo
            $equipo->server_key = $validatedData['server_key'];
            $equipo->mac = $validatedData['mac'];
            $equipo->save();

            // Determinar fechas de inicio y final según el período seleccionado
            $licencia_inicio = Carbon::now();
            $licencia_final = match ($validatedData['periodo']) {
                'PRUEBA' => $licencia_inicio->copy()->addDays(7),
                'MENSUAL' => $licencia_inicio->copy()->addMonth(),
                'TRIMESTRAL' => $licencia_inicio->copy()->addMonths(3),
                'SEMESTRAL' => $licencia_inicio->copy()->addMonths(6),
                'ANUAL' => $licencia_inicio->copy()->addYear(),
                default => $licencia_inicio->copy()->addMonth(), // Default a 1 mes
            };

            // Checar si la licencia ya existe
            $licenciaExistente = Licencias::where('licencia', $validatedData['licencia'])->first();

            if (!$licenciaExistente) {
                // Guardar la licencia si no existe
                $licencia = new Licencias();
                $licencia->licencia = $validatedData['licencia'];
                $licencia->periodo = $validatedData['periodo'];
                $licencia->licencia_inicio = $licencia_inicio;
                $licencia->licencia_final = $licencia_final;
                //$licencia->equipo_id = $equipo->equipo_id; //SOLO SE RELACIONARA LA LICENCIA CON EL EQUIPO CUANDO EL EQUIPO AL AGREGAR LA LICENCIA
                $licencia->save();
            }

            // Devolver una respuesta exitosa
            return response()->json(['message' => 'Server Key y licencia guardadas correctamente'], 200);
        } catch (\Exception $e) {
            \Log::error('Error al agregar la server key: ' . $e->getMessage());
            return response()->json(['message' => 'Error al agregar la server key'], 500);
        }
    }
}