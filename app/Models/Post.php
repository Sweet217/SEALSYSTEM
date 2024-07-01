<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function author() {
        
    }
}

//RECUERDO QUE POST SOLO FUE PARA PRUEBAS, RECOMIENDO DEJARLO POR SI ES NECESARIA ALGUN TIPO DE PRUEBA.