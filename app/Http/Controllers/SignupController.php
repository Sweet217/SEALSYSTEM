<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Log;

class SignupController extends Controller
{
    public function signup(Request $request)
{
    try {
        $validatedData = $request->validate([
            'nombre' => 'required',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|confirmed',
            'tipo_usuario' => 'required|in:Operador,Administrador',
            'telefono' => 'required',
            'estado' => 'required|in:Activo,Inactivo',
        ]);
        
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = Users::create($validatedData);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('Error creating user: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to create user'], 500);
    }
}


}