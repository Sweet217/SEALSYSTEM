<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Login;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Generate a more descriptive token name
            $tokenName = $user->correo . '-api-token';
            return $user->createToken($tokenName)->plainTextToken;
        }

        return response()->json(['error' => 'Correo o contraseña incorrectos'], 401);
    }
}