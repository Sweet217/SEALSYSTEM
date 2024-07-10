<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Representa una licencia en la base de datos.
 *
 * Una licencia puede estar asociada a un equipo (`Equipos`) a través de `equipo_id`.
 */

class licencias extends Model
{
    use HasFactory;

    protected $table = 'licencias';
    protected $primaryKey = 'licencia_id';
    /**
     * Campos asignables masivamente.
     *
     * @var array
     */
    protected $fillable = ['equipo_id', 'licencia_inicio', 'licencia_final', 'periodo'];

    /**
     * Relación con la tabla 'equipos'.
     *
     * Una licencia puede estar asociada a un equipo. (Relación uno a uno inversa)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function equipos()
    {
        return $this->hasOne(Equipos::class, 'licencia_id');
    }
}