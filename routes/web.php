<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ListasController;
use App\Http\Controllers\MultimediaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EquiposController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\EnlaceController;
use App\Http\Controllers\SignupController;

use App\Models\listas;
use App\Models\Users;
use App\Models\equipos;

//Login & register
Route::post('/loginPOST', [LoginController::class, 'login']);
Route::post('/signupPOST', [SignupController::class, 'signup']);

Route::get('/', function () {
    return Inertia::render('Login', [
    ]);
});

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
    
    Route::get('/listas/{id_lista}/multimedia', [MultimediaController::class, 'showMultimedia']);

    Route::get('/listas', [ListasController::class, 'showListas']);
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

    // Route::get('/usuario_actual', [UserController::class, 'usuarioActual']);

    // Rutas para Equipos
    Route::get('/equipos', [EquiposController::class, 'showEquipos'])->name('equipos');
    Route::post('/equiposPOST', [EquiposController::class, 'crearEquipos']);
    Route::delete('/equiposDELETE/{equipo_id}', [EquiposController::class, 'borrarEquipos']);
    Route::put('/equiposPUT/{equipo_id}', [EquiposController::class, 'editarEquipos']);

    Route::get('/equipos/{equipo_id}', function ($equipo_id) {
        $equipo = equipos::where('equipo_id', $equipo_id)->first(); // Saca el equipo por ID

        if ($equipo_id) {
            return "Mi nombre es $equipo->nombre"; // Retorna el nombre del equipo
        } else {
            return abort(404); // Handle not found case
        }
    });

    // //Rutas para multimedia
    // Route::post('/multimediaPOST', [MultimediaController::class, 'crearMultimedia']);
    Route::put('/actualizarOrdenMultimedia', [MultimediaController::class, 'actualizarOrden']);

    //Rutas para Imagenes
    Route::put('/imagenPUT/{imagen_id}', [ImagenController::class, 'editarImagen']);
    Route::post('/imagenesPOST', [ImagenController::class, 'crearImagen']);
    Route::delete('/imagenesDELETE/{multimedia_id}/{imagen_id}', [ImagenController::class, 'eliminarImagen']);

    //Rutas para Videos
    Route::post('/videosPOST', [VideoController::class, 'crearVideo']);
    Route::delete('/videosDELETE/{multimedia_id}/{video_id}', [VideoController::class, 'eliminarVideo']);
    
    //Rutas para Enlaces
    Route::post('/enlacesPOST', [EnlaceController::class, 'crearEnlace']);
    Route::delete('/enlacesDELETE/{multimedia_id}/{enlace_id}', [EnlaceController::class, 'eliminarEnlace']);


// Route::middleware('auth:sanctum')->group(function () {

//     Route::post('/signupPOST', [SignupController::class, 'signup']);
//     Route::get('/register', function () {
//         return Inertia::render('SignUp', [
//         ]);
//     });
    
//     // Rutas para la página principal
//     Route::get('/pantallaprincipal', function() {
//         return Inertia::render('PaginaPrincipal', []);
//     });

//     // Rutas para Listas
//     Route::get('/listas/{id_lista}', function ($id_lista) {
//         $lista = Listas::where('id_lista', $id_lista)->first(); // Fetch the list by ID

//         if ($lista) {
//             return "Mi nombre es $lista->nombre"; // Return list name
//         } else {
//             return abort(404); // Handle not found case
//         }
//     });

//     Route::get('/listas', [ListasController::class, 'showListas'])->name('listas');
//     Route::post('/listasPOST', [ListasController::class, 'crearLista']);
//     Route::delete('/listasDELETE/{id_lista}', [ListasController::class, 'borrarLista']);
//     Route::put('/listasPUT/{id_lista}', [ListasController::class, 'editarLista']);

//     // Rutas para Usuarios
//     Route::get('/usuarios', [UserController::class, 'showUsuarios'])->name('usuarios');
//     Route::post('/usuariosPOST', [UserController::class, 'crearUsuario']);
//     Route::delete('/usuariosDELETE/{user_id}', [UserController::class, 'borrarUsuario']);
//     Route::put('/usuariosPUT/{user_id}', [UserController::class, 'editarUsuario']);

//     Route::get('/usuarios/{user_id}', function ($user_id) {
//         $usuario = Users::where('user_id', $user_id)->first(); // Saca el usuario por ID

//         if ($user_id) {
//             return "Mi nombre es $usuario->nombre"; // Retorna el nombre del usuario
//         } else {
//             return abort(404); 
//         }
//     });

//     Route::get('/usuario_actual', [UserController::class, 'usuarioActual']);

//     // Rutas para Equipos
//     Route::get('/equipos', [EquiposController::class, 'showEquipos'])->name('equipos');
//     Route::post('/equiposPOST', [EquiposController::class, 'crearEquipos']);
//     Route::delete('/equiposDELETE/{equipo_id}', [EquiposController::class, 'borrarEquipos']);
//     Route::put('/equiposPUT/{equipo_id}', [EquiposController::class, 'editarEquipos']);

//     Route::get('/equipos/{equipo_id}', function ($equipo_id) {
//         $equipo = Equipos::where('equipo_id', $equipo_id)->first(); // Saca el equipo por ID

//         if ($equipo_id) {
//             return "Mi nombre es $equipo->nombre"; // Retorna el nombre del equipo
//         } else {
//             return abort(404); // Handle not found case
//         }
//     });

//     // Ejemplo de un recurso protegido
//     Route::get('/protected-resource', function () {
//         return response()->json(['message' => 'This is a protected resource']);
//     });
// });





Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});