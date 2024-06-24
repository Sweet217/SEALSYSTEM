<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
Use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
Use App\Models\Users; //Importar el modelo users
 
class LoginController extends Controller
{
   /**
 * Maneja las solicitudes de inicio de sesión del usuario y devuelve una respuesta JSON
 * con el estado de autenticación y la información del usuario tras un inicio de sesión exitoso.
 *
 * @param Request $request El objeto de solicitud HTTP que contiene las credenciales de inicio de sesión.
 * @return JsonResponse Una respuesta JSON que indica el éxito o el fracaso del inicio de sesión.
 *
 * @throws \Illuminate\Validation\ValidationException Se lanza si las credenciales de inicio de sesión
 *         no son válidas o faltan.
 */
public function login(Request $request): JsonResponse
{
    // Valida las credenciales de inicio de sesión utilizando el mecanismo de validación integrado de Laravel.
    // Esto garantiza la integridad de los datos y evita posibles vulnerabilidades de seguridad.
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Intenta la autenticación del usuario utilizando el método `Auth::attempt` de Laravel.
    // Este método compara las credenciales proporcionadas con la base de datos de usuarios
    // y devuelve `true` si tiene éxito.
    if (Auth::attempt($credentials)) {
        // Recupera el objeto de usuario autenticado utilizando `Auth::user()`.
        $user = Auth::user();

        // Crea un nuevo token de autenticación para el usuario utilizando el método `createToken` de Laravel.
        // Este token se utilizará en las posteriores solicitudes de la API para identificar
        // al usuario autenticado. La propiedad `plainTextToken` proporciona el valor real
        // del token en texto claro.
        $token = $user->createToken('auth_token')->plainTextToken;

        // Construye una respuesta de inicio de sesión exitosa como un objeto JSON.
        return response()->json([
            'autenticacion_correcta' => true, // Indicador de autenticación exitosa
            'token' => $token,                // El token de autenticación generado
            'usuario' => [                   // Información del usuario para el cliente
                'user_id' => $user->id,
                'nombre' => $user->nombre, // Suponiendo que 'nombre' es el campo de nombre del usuario
            ]
        ]);
    } else {
        // El inicio de sesión ha fallado: devuelve una respuesta JSON que indica el fallo de la autenticación
        // con un código de estado HTTP 401 No autorizado.
        return response()->json(['autenticacion_correcta' => false], 401);
    }
}
}