<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class multimedia extends Model
{
    use HasFactory;

    protected $table = 'multimedia';
    protected $primaryKey = 'multimedia_id';
    protected $fillable = ['tipo', 'posicion', 'id_lista'];

    protected static function boot()
    {
        parent::boot();

        // Cuando se crea una nueva instancia de Multimedia
        static::creating(function ($multimedia) {
            // Obtener la posición máxima actual para la lista específica
            $maxPosition = self::where('id_lista', $multimedia->id_lista)->max('posicion') ?? 0;
            
            // Asignar el siguiente número de posición dentro de la lista específica
            $multimedia->posicion = $maxPosition + 1;
        });
    }

    public function lista()
    {
        return $this->belongsTo(listas::class, 'id_lista', 'id_lista');
    }

    public function video()
    {
        return $this->hasOne(videos::class, 'multimedia_id', 'multimedia_id');
    }

    public function imagen()
    {
        return $this->hasOne(imagenes::class, 'multimedia_id', 'multimedia_id');
    }

    public function enlace()
    {
        return $this->hasOne(enlaces::class, 'multimedia_id', 'multimedia_id');
    }
}