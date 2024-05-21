<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
Use Illuminate\Http\JsonResponse;
 
class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required'],
        ]);    

        if (Auth::attempt($credentials)) {
            return response()->json(['autenticacion_correcta' => true]);
        } else {
            return response()->json(['autenticacion_correcta' => false]);
        }
    }
}