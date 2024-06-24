<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Representa una imagen almacenada en el sistema.
 *
 * Una imagen está asociada a un registro en la tabla 'multimedia'.
 */

class imagenes extends Model
{
    use HasFactory;

    protected $table = 'imagenes';
    protected $primaryKey = 'imagen_id';
    /**
     * Campos asignables masivamente.
     *
     * @var array
     */
    protected $fillable = ['nombre_archivo', 'tiempo', 'data', 'multimedia_id'];

    /**
     * Relación con la tabla 'multimedia'.
     *
     * Una imagen pertenece a un registro de multimedia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function multimedia()
    {
        return $this->belongsTo(Multimedia::class, 'multimedia_id', 'multimedia_id');
    }
}