<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use Inertia\Inertia;

Route::get('/user', function (Request $request) {
    return $request-user();
})->middleware('auth:sanctum');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware("auth:sanctum")->group(function(){
    Route::get("/posts", [PostController::class, "index"]);
    Route::post("/post",[PostController::class, "createPost"]);
});

Route::middleware("auth:sanctum")->group(function () {

    Route::post('/signupPOST', [SignupController::class, 'signup']);
    Route::get('/register', function () {
        return Inertia::render('SignUp', [
        ]);
    });
    
    // Rutas para la página principal
    Route::get('/pantallaprincipal', function() {
        return Inertia::render('PaginaPrincipal', []);
    });

    // Rutas para Listas
    Route::get('/listas/{id_lista}', function ($id_lista) {
        $lista = Listas::where('id_lista', $id_lista)->first(); // Fetch the list by ID

        if ($lista) {
            return "Mi nombre es $lista->nombre"; // Return list name
        } else {
            return abort(404); // Handle not found case
        }
    });
    Route::post('/listasPOST', [ListasController::class, 'crearLista']);
    Route::delete('/listasDELETE/{id_lista}', [ListasController::class, 'borrarLista']);
    Route::put('/listasPUT/{id_lista}', [ListasController::class, 'editarLista']);

    // Rutas para Usuarios
    Route::get('/usuarios', [UserController::class, 'showUsuarios'])->name('usuarios');
    Route::post('/usuariosPOST', [UserController::class, 'crearUsuario']);
    Route::delete('/usuariosDELETE/{user_id}', [UserController::class, 'borrarUsuario']);
    Route::put('/usuariosPUT/{user_id}', [UserController::class, 'editarUsuario']);

    Route::get('/usuarios/{user_id}', function ($user_id) {
        $usuario = Users::where('user_id', $user_id)->first(); // Saca el usuario por ID

        if ($user_id) {
            return "Mi nombre es $usuario->nombre"; // Retorna el nombre del usuario
        } else {
            return abort(404); 
        }
    });

    Route::get('/usuario_actual', [UserController::class, 'usuarioActual']);



    // Rutas para Equipos
    Route::get('/equipos', [EquiposController::class, 'showEquipos'])->name('equipos');
    Route::post('/equiposPOST', [EquiposController::class, 'crearEquipos']);
    Route::delete('/equiposDELETE/{equipo_id}', [EquiposController::class, 'borrarEquipos']);
    Route::put('/equiposPUT/{equipo_id}', [EquiposController::class, 'editarEquipos']);

    Route::get('/equipos/{equipo_id}', function ($equipo_id) {
        $equipo = Equipos::where('equipo_id', $equipo_id)->first(); // Saca el equipo por ID

        if ($equipo_id) {
            return "Mi nombre es $equipo->nombre"; // Retorna el nombre del equipo
        } else {
            return abort(404); // Handle not found case
        }
    });

    // Ejemplo de un recurso protegido
    Route::get('/protected-resource', function () {
        return response()->json(['message' => 'This is a protected resource']);
    });
});