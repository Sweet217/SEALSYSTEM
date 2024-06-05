<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
Use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
Use App\Models\Users;
 
class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'autenticacion_correcta' => true,
                'token' => $token,
                'usuario' => [
                    'user_id' => $user->id,
                    'nombre' => $user->nombre,
                ]
            ]);
        } else {
            return response()->json(['autenticacion_correcta' => false], 401);
        }
    }
}