<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Login;
use Spatie\Permission\Models\Role;
use App\Models\Users;

class UserController extends Controller
{
    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

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