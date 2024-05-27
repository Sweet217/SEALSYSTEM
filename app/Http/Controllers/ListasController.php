<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\listas;

class ListasController extends Controller
{
    public function showListas(Request $request)
  {
    $data = listas::all();

    return Inertia::render('Listas', [
        'listas' => $data
    ]);
  }

  public function crearLista(Request $request) {
    // Valida los datos del formulario
    $request->validate([
        'nombre' => 'required', // Asegura que el nombre sea obligatorio y tenga como máximo 255 caracteres
    ]);

    // Crea una nueva instancia del modelo Lista
    $lista = new listas();
    $lista->nombre = $request->nombre; // Asigna el nombre de la lista desde el formulario

    try {
        $lista->save();
        // Si la lista se guarda correctamente, retorna una respuesta de éxito
        return response()->json(['message' => 'Lista creada correctamente'], 201);
    } catch (\Exception $e) {
        // Si ocurre un error al guardar la lista, retorna un mensaje de error
        return response()->json(['error' => 'Error al crear la lista'], 500);
    }
}

  public function borrarLista(Request $request, $id_lista) {
    
    $lista = Listas::where('id_lista', $id_lista)->first();
    $lista->delete();

    return response()->json(['message' => 'Lista eliminada correctamente'], 205);
  }
  
  public function editarLista(Request $request, $id_lista) {
    //Validar se haya proporcionado un nombre
    $request->validate([
      'nombre' => 'required'
    ]);
    //Encontrar el id de la lista   
    $lista = Listas::where('id_lista', $id_lista)->first();

    //Actualizar el nombre de la lista
    $lista->nombre = $request->input('nombre');
    $lista->save();

    return response()->json(['message' => 'Lista editada `correctamente'], 205);
  }

  

  
}