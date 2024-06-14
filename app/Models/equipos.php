<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class equipos extends Model
{
    
    use HasFactory;

    protected $primaryKey = 'equipo_id'; // Set the primary key field
    protected $fillable = ['licencia_id', 'user_id', 'nombre']; // Add other fields as necessary

    public function usuarios()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function licencias()
    {
        return $this->belongsTo(licencias::class, 'licencia_id');
    }
    
    public function listas()
    {
        return $this->hasMany(listas::class, 'equipo_id');
    }
    
}