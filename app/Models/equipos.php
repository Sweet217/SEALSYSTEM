<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class equipos extends Model
{
    use HasFactory;

    protected $primaryKey = 'equipo_id'; // Set the primary key field
    protected $fillable = ['licencia_id', 'usuario_id', 'nombre']; // Add other fields as necessary
    
}