<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Login;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;
use App\Models\Users;

class UserController extends Controller
{
    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function showUsuarios(Request $request)
        {
            $data = Users::all();

            return Inertia::render('Usuarios', [
                'usuarios' => $data
            ]);
        }
 
     public function borrarUsuario(Request $request, $usuario_id)
     {
         $usuario = Users::find($usuario_id);
 
         if (!$usuario) {
             return response()->json(['message' => 'Usuario no encontrado'], 404);
         }
 
         $usuario->delete();
 
         return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
     }
 
     public function editarUsuario(Request $request, $usuario_id)
     {
         $request->validate([
             'nombre' => 'required',
             'correo' => 'required|email',
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
         $usuario->correo = $request->correo;
         $usuario->telefono = $request->telefono;
         $usuario->estado = $request->estado;
         $usuario->tipo_usuario = $request->tipo_usuario;
         // Actualiza otros campos según sea necesario
 
         $usuario->save();
 
         return response()->json(['message' => 'Usuario editado correctamente'], 200);
     }

     public function AsignarRolesAUsuarios(Request $request)
     {
         // Obtener el usuario por ID
         $user = Users::find($request->user_id); 
 
         if (!$user) {
             return response()->json(['message' => 'User not found'], 404);
         }
 
         // Asignar rol basado en tipo_usuario
         if ($user->tipo_usuario === 'Operador') {
             $user->assignRole('Operador');
         } elseif ($user->tipo_usuario === 'Administrador') {
             $user->assignRole('Administrador');
         } else {
             return response()->json(['message' => 'Usuario invalido'], 400);
         }
         return response()->json(['message' => 'Rol asignado correctamente']);
     }
}