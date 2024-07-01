<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;
use App\Models\Users;

class UserController extends Controller
{
    /**
     * Obtiene una lista de todos los usuarios del sistema.
     *
     * @param Request $request Objeto de solicitud HTTP (puede no usarse en esta función).
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentran usuarios en la base de datos.
     */
    public function showUsuarios(Request $request)
    {
        $data = Users::all();

        return Inertia::render('Usuarios', [
            'usuarios' => $data
        ]);
    }

    public function usuarios(Request $request)
    {
        $data = Users::all();

        return $data;
    }

    /**
     * Obtiene información del usuario actualmente autenticado.
     *
     * @param Request $request Objeto de solicitud HTTP que contiene información del usuario autenticado.
     * @return JsonResponse Respuesta JSON con la información del usuario o un mensaje de error si no está autenticado.
     */
    public function usuarioActual(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            return response()->json([
                'user_id' => $user->user_id,
                'nombre' => $user->nombre,
                'tipo_usuario' => $user->tipo_usuario,
                'email' => $user->email
            ]);
        } else {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }
    }

    /**
     * Elimina un usuario del sistema.
     *
     * @param Request $request Objeto de solicitud HTTP (puede no usarse en esta función).
     * @param int $usuario_id Identificador del usuario a eliminar.
     * @return \Illuminate\Http\Response Respuesta JSON con un mensaje de éxito o error.
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el usuario con el ID especificado.
     */
    public function borrarUsuario(Request $request, $usuario_id)
    {
        $usuario = Users::find($usuario_id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $usuario->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }

    /**
     * Actualiza la información de un usuario existente.
     *
     * @param Request $request Objeto de solicitud HTTP que contiene los datos nuevos del usuario.
     * @param int $usuario_id Identificador del usuario a editar.
     * @return \Illuminate\Http\Response Respuesta JSON con un mensaje de éxito o error.
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el usuario con el ID especificado.
     */
    public function editarUsuario(Request $request, $usuario_id)
    {
        $request->validate([
            'nombre' => 'required',
            'email' => 'required|email',
            'telefono' => 'required',
            'estado' => 'required',
            'tipo_usuario' => 'required'
            // Puedes agregar más validaciones según tus requisitos
        ]);

        $usuario = Users::find($usuario_id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
        $usuario->estado = $request->estado;
        $usuario->tipo_usuario = $request->tipo_usuario;
        // Actualiza otros campos según sea necesario

        $usuario->save();

        return response()->json(['message' => 'Usuario editado correctamente'], 200);
    }

    /**
     * Asigna un rol a un usuario en base a su tipo de usuario.
     *
     * @param Request $request Objeto de solicitud HTTP que contiene el ID del usuario.
     * @return \Illuminate\Http\Response Respuesta JSON con un mensaje de éxito o error.
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el usuario con el ID especificado.
     */
    public function AsignarRolesAUsuarios(Request $request)
    {
        // Obtener el usuario por ID
        $user = Users::find($request->user_id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Asignar rol basado en tipo_usuario
        if ($user->tipo_usuario === 'Operador') {
            $user->assignRole('Operador');
        } elseif ($user->tipo_usuario === 'Administrador') {
            $user->assignRole('Administrador');
        } else {
            return response()->json(['message' => 'Usuario inválido'], 400);
        }

        return response()->json(['message' => 'Rol asignado correctamente']);
    }
}