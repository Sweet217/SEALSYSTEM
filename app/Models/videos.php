<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Representa un video almacenado en el sistema.
 *
 * Un video está asociado a un registro en la tabla 'multimedia'.
 */
class videos extends Model
{
    use HasFactory;

    protected $table = 'videos';
    protected $primaryKey = 'video_id';
    /**
     * Campos asignables masivamente.
     *
     * @var array
     */
    protected $fillable = ['nombre_archivo', 'data', 'multimedia_id'];

    /**
     * Relación con la tabla 'multimedia'.
     *
     * Un video pertenece a un elemento multimedia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function multimedia()
    {
        return $this->belongsTo(Multimedia::class, 'multimedia_id', 'multimedia_id');
    }
}