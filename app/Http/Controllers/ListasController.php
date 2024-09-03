<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\listas; //Importar el modelo listas
use App\Models\equipos; //Importar el modelo equipos
use App\Http\Controllers\UserController;

class ListasController extends Controller
{

  /* Mostrar las listas que esten asociadas a un equipo*/
  public function showListas(Request $request)
  {
    $listas = listas::with('equipo')->get();
    $equipos = equipos::all();

    return Inertia::render('Listas', [
      'listas' => $listas,
      'equipos' => $equipos
    ]);

  }

  public function crearLista(Request $request)
  {
    // Valida los datos del formulario
    $request->validate([
      'nombre' => 'required',
      'user_id' => 'required',
      'equipo_id' => 'required'
    ]);

    $existeLista = Listas::where('nombre', $request->input('nombre'))->exists();
    if ($existeLista) {
      return response()->json(['error' => 'Nombre de lista ya registrado'], 400);
    }

    // Crea una nueva instancia del modelo Lista
    $lista = new Listas();
    $lista->nombre = $request->input('nombre'); // Asigna el nombre de la lista desde el formulario
    $equipo_id = $request->input('equipo_id');

    // Obtener el user_id del request
    $user_id = $request->input('user_id');

    if ($equipo_id === 'todos') {
      $lista->equipo_id = null;
      $lista->user_id = $user_id;
    } else {
      $equipo = Equipos::where('equipo_id', $equipo_id)->where('user_id', $user_id)->first();
      if (!$equipo) {
        return response()->json(['error' => 'El equipo no pertenece al usuario especificado'], 400);
      }
      // Asignar lista a un equipo específico del usuario
      $lista->equipo_id = $equipo_id;
      $lista->user_id = $user_id;
    }

    try {
      $lista->save();
      // Si la lista se guarda correctamente, retorna una respuesta de éxito
      return response()->json(['message' => 'Lista creada correctamente'], 201);
    } catch (\Exception $e) {
      // Si ocurre un error al guardar la lista, retorna un mensaje de error
      return response()->json(['error' => 'Error al crear la lista'], 500);
    }
  }

  public function borrarLista(Request $request, $id_lista)
  {

    $lista = Listas::where('id_lista', $id_lista)->first();
    $lista->delete();

    return response()->json(['message' => 'Lista eliminada correctamente'], 205);
  }

  public function editarLista(Request $request, $id_lista)
  {
    $request->validate([
      'nombre' => 'required',
      'user_id' => 'required',
      'equipo_id' => 'required'
    ]);

    $nuevoNombre = $request->input('nombre');
    $lista = Listas::where('id_lista', $id_lista)->first();
    $lista->nombre = $request->input('nombre');
    $equipo_id = $request->input('equipo_id');

    // Verificar si el nuevo nombre de lista ya existe (excepto la lista actual)
    $existeOtroNombre = Listas::where('nombre', $nuevoNombre)
      ->where('id_lista', '!=', $id_lista) // Excluye la lista actual
      ->exists();

    if ($existeOtroNombre) {
      return response()->json(['error' => 'Nombre de lista ya registrado'], 400);
    }

    // Obtener el user_id del request
    $user_id = $request->input('user_id');

    if ($equipo_id === 'todos') {
      $lista->equipo_id = null;
      $lista->user_id = $user_id;
    } else {
      // Asignar lista a un equipo específico del usuario
      $lista->equipo_id = $equipo_id;
      $lista->user_id = $user_id;
    }

    $lista->save();

    return response()->json(['message' => 'Lista editada correctamente'], 205);
  }

  public function seleccionarLista(Request $request, $id_lista)
  {
    // Fetch the user_id from the request or session
    $user_id = $request->input('user_id'); // Make sure this is passed in the request

    // Deseleccionar todas las listas para este usuario
    Listas::where('user_id', $user_id)->update(['seleccionado' => false]);

    // Seleccionar la lista especificada
    $lista = Listas::where('id_lista', $id_lista)
      ->where('user_id', $user_id) // Ensure that the list belongs to the user
      ->first();

    if (!$lista) {
      return response()->json(['error' => 'Lista no encontrada o no pertenece al usuario'], 404);
    }

    $lista->seleccionado = true;
    $lista->save();

    return response()->json(['message' => 'Tras la descarga de la lista, esta sera reproducida']);
  }



}