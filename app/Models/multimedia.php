<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Representa un elemento multimedia en la base de datos.
 *
 * Un elemento multimedia puede ser un video, una imagen, un enlace, etc. 
 * Se define por su tipo (`tipo`) y su posición (`posicion`) dentro de una lista (`listas`).
 * 
 * Un elemento multimedia pertenece a una lista y puede ser:
 *  * Un video (relación con la tabla 'videos')
 *  * Una imagen (relación con la tabla 'imagenes')
 *  * Un enlace (relación con la tabla 'enlaces')
 */
class multimedia extends Model
{
    use HasFactory;

    protected $table = 'multimedia';
    protected $primaryKey = 'multimedia_id';
    /**
     * Campos asignables masivamente.
     *
     * @var array
     */
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

     /**
     * Relación con la tabla 'listas'.
     *
     * Un elemento multimedia pertenece a una lista.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lista()
    {
        return $this->belongsTo(listas::class, 'id_lista', 'id_lista');
    }
    /**
     * Relación polimórfica con la tabla 'videos'.
     *
     * Un elemento multimedia puede ser un video.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function video()
    {
        return $this->hasOne(videos::class, 'multimedia_id', 'multimedia_id');
    }
    /**
     * Relación polimórfica con la tabla 'imagenes'.
     *
     * Un elemento multimedia puede ser una imagen.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function imagen()
    {
        return $this->hasOne(imagenes::class, 'multimedia_id', 'multimedia_id');
    }
    /**
     * Relación polimórfica con la tabla 'enlaces'.
     *
     * Un elemento multimedia puede ser un enlace.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function enlace()
    {
        return $this->hasOne(enlaces::class, 'multimedia_id', 'multimedia_id');
    }
}