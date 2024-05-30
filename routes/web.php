<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ListasController;
use App\Http\Controllers\MultimediaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

use App\Models\listas;
use App\Models\Users;
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
Route::delete('/usuariosDELETE/{usuario_id}', [UserController::class, 'borrarUsuario']);
Route::put('/usuariosPUT/{usuario_id}', [UserController::class, 'editarUsuario']);
////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/usuarios/{usuario_id}', function ($usuario_id) {
    $usuario = Users::where('usuario_id', $usuario_id)->first(); // Saca el usuario por ID

    if ($usuario_id) {
        return "Mi nombre es $usuario->nombre"; // Retorna el nombre del usuario
    } else {
        return abort(404); 
    }
});

//Equipos
Route::get('/equipos', [EquiposController::class, 'showEquipos'])->name('equipos');
Route::post('/equiposPOST', [EquiposController::class, 'crearEquipos']);
Route::delete('/equiposDELETE/{equipo_id}', [EquiposController::class, 'borrarEquipos']);
Route::put('/equiposPUT/{equipo_id}', [EquiposController::class, 'editarEquipos']);
////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/equipos/{equipo_id}', function ($equipo_id) {
    $equipo = Equipos::where('equipo_id', $equipo_id)->first(); // Saca el equipo por ID

    if ($usuario_id) {
        return "Mi nombre es $equipo->nombre"; // ReturnA EL NOMBRE DEL EQUIPO
    } else {
        return abort(404); // Handle not found case
    }
});

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