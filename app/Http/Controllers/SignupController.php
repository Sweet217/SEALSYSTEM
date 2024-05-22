<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;

class SignupController extends Controller
{
    public function signup(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:users', //Email unico
            'password' => 'required|string|min:8|confirmed', // que haya confirmado su password
            'tipo_usuario' => 'required|in:Operador,Administrador', // que haya seleccionado un tipo de usuario / rol
        ]);

        $user = User::create([
            'nombre' => $validatedData['nombre'],
            'correo' => $validatedData['correo'],
            'password' => Hash::make($validatedData['password']),
            'tipo_usuario' => $validatedData['tipo_usuario'],
        ]);

        $role = $validatedData['tipo_usuario'] === 'Operador' ? $operadorRole : $adminstratorRole;
        $user->attachRole($role);
    }
}