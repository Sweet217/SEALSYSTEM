<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class licencias extends Model
{
    use HasFactory;

    protected $table = 'licencias';
    protected $primaryKey = 'licencia_id'; 
    protected $fillable = ['equipo_id'];

    public function equipos()
    {
        return $this->hasOne(Equipos::class, 'licencia_id');
    }
}