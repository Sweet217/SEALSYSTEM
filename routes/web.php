<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ListasController;
use App\Http\Controllers\MultimediaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

use App\Models\listas;

Route::get('/', function () {
    return Inertia::render('Login', [
    ]);
});

Route::get('/pantallaprincipal', function() {
    return Inertia::render('PaginaPrincipal', [
    ]);
});

Route::get('/listas/{id_lista}', function ($id_lista) {
    $lista = Listas::where('id_lista', $id_lista)->first(); // Fetch the list by ID

    if ($lista) {
        return "Mi nombre es $lista->nombre"; // Return list name
    } else {
        return abort(404); // Handle not found case
    }
});

//Listas
Route::get('/listas', [ListasController::class, 'showListas'])->name('listas');
Route::post('/listasPOST', [ListasController::class, 'crearLista']);
Route::delete('/listasDELETE/{id_lista}', [ListasController::class, 'borrarLista']);
Route::put('/listasPUT/{id_lista}', [ListasController::class, 'editarLista']);

  
//Usuarios
Route::get('/usuarios', [UserController::class, 'showUsuarios'])->name('usuarios');
Route::post('/usuariosPOST', [UserController::class, 'crearUsuario']);
Route::delete('/usuariosDELETE/{id_lista}', [UserController::class, 'borrarUsuario']);
Route::put('/usuariosPUT/{id_lista}', [UserController::class, 'editarUsuario']);


Route::get('/Perfil', function () {
    return Inertia::render('Perfil', [
    ]);
});

// Render Login Page (GET request)
Route::get('/loginsolytec', function () {
    return Inertia::render('Login');
  });
  
// Handle Login Logic (POST request)
Route::post('/loginPOST', [LoginController::class, 'login']);


Route::get('/signupsolytec', function () {
    return Inertia::render('SignUp', [
    ]);
});

Route::post('signupPOST', [SignupController::class, 'register']);



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});