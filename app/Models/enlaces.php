<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Representa un enlace (link) en la base de datos.
 *
 * Un enlace está asociado a un registro en la tabla 'multimedia'.
 */
class enlaces extends Model
{
    protected $table = 'enlaces';
    protected $primaryKey = 'enlace_id';

    /**
     * Campos asignables masivamente.
     *
     * @var array
     * 
     */
    protected $fillable = ['data', 'multimedia_id'];

    /**
     * Relación con la tabla 'multimedia'.
     *
     * Un enlace pertenece a un registro de multimedia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function multimedia()
    {
        return $this->belongsTo(Multimedia::class, 'multimedia_id', 'multimedia_id');
    }
}