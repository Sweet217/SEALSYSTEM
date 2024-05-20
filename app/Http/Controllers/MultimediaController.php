<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\multimedia;
use app\Modelos\lista;

class MultimediaController extends Controller
{
    public function showMultimedia(Request $request, $listId)
  {
    $lista = Lista::find($listId); 

    if (!$lista) {
      return response()->json(['error' => 'Invalid list ID'], 404);
    }

    $data['listaData'] = $lista;
    $data['multimedia'] = $lista->multimedia; 

    return Inertia::render('mainpage', $data);
  }
}