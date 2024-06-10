<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class imagenes extends Model
{
    use HasFactory;

    protected $table = 'imagenes';
    protected $primaryKey = 'imagen_id';
    protected $fillable = ['nombre_archivo', 'tiempo', 'data', 'multimedia_id'];

    public function multimedia()
    {
        return $this->belongsTo(Multimedia::class, 'multimedia_id', 'multimedia_id');
    }
}