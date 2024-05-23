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
    $data = Listas::all();

    return Inertia::render('Listas', [
        'listas' => $data
    ]);
  }

  public function crearLista(Request $request) {
    
  }
  
}