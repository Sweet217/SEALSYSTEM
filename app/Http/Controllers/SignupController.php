<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Log;

class SignupController extends Controller
{
   /**
 * Registra un nuevo usuario en el sistema.
 *
 * @param Request $request Objeto de solicitud HTTP que contiene los datos del formulario de registro.
 * @return \Illuminate\Http\Response Respuesta JSON con el usuario creado y el token de autenticación, o un mensaje de error.
 */
    public function signup(Request $request)
    {
    try {
        //  Validar los datos del formulario
        $validatedData = $request->validate([
        'nombre' => 'required', // El campo `nombre` es obligatorio.
        'email' => 'required|email|unique:usuarios,email', // El campo `email` es obligatorio, debe ser un email válido y único en la tabla `usuarios` con el campo `email`.
        'password' => 'required|confirmed', // El campo `password` es obligatorio y debe coincidir con el campo `password_confirmation`.
        'tipo_usuario' => 'required|in:Operador,Administrador', // El campo `tipo_usuario` es obligatorio y debe ser "Operador" o "Administrador".
        'telefono' => 'required', // El campo `telefono` es obligatorio.
        'estado' => 'required|in:Activo,Inactivo', // El campo `estado` es obligatorio y debe ser "Activo" o "Inactivo".
        ]);

        //  Hashear la contraseña
        $validatedData['password'] = bcrypt($validatedData['password']); // Hashea la contraseña utilizando bcrypt para mayor seguridad.

        //  Crear el usuario
        $user = Users::create($validatedData); // Crea un nuevo registro en la tabla `Users` con los datos validados.

        //  Generar token de autenticación
        $token = $user->createToken('auth_token')->plainTextToken; // Genera un token de autenticación para el usuario recién creado.

        //  Devolver respuesta exitosa
        return response()->json([
        'user' => $user, // Devolver el objeto del usuario creado.
        'token' => $token, // Devolver el token de autenticación.
        ], 201); // Código de estado HTTP 201 ("Created") para indicar creación exitosa.

    } catch (ValidationException $e) {
        // Manejar errores de validación
        return response()->json(['errors' => $e->errors()], 422); // Código de estado HTTP 422 ("Unprocessable Entity") para indicar errores de validación.
    } catch (\Exception $e) {
        // Manejar errores generales
        \Log::error('Error creating user: ' . $e->getMessage()); // Registrar el error en el sistema de logs.
        return response()->json(['error' => 'Failed to create user'], 500); // Código de estado HTTP 500 ("Internal Server Error") para indicar un error general.
    }
    }

}