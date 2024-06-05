<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\equipos;
use App\Models\Users;
use App\Models\licencias;

class EquiposController extends Controller
{
    /**
     * Display a listing of the equipos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showEquipos(Request $request)
    {
        $data = Equipos::with('usuarios', 'licencias')->get();

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
            ]);

            // Buscar el ID del usuario por su nombre
            $usuario = Users::where('nombre', $request->input('nombre_usuario'))->first();
            if (!$usuario) {
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }

            // Buscar el ID de la licencia por su número (puede ser null)
            $licencia = Licencias::where('licencia', $request->input('numero_licencia'))->first();
            $licencia_id = $licencia ? $licencia->licencia_id : null;

            // Crear el nuevo equipo
            $equipo = new Equipos();
            $equipo->nombre = $request->input('nombre');
            $equipo->licencia_id = $licencia_id;
            $equipo->user_id = $usuario->user_id;

            $equipo->save();

            // Actualizar la licencia si existe
            if ($licencia) {
                $licencia->equipo_id = $equipo->equipo_id;
                $licencia->save();
            }

            return response()->json(['message' => 'Equipo creado correctamente', 'equipo' => $equipo], 201);
        }


    /**
     * Remove the specified equipo from storage.
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
}