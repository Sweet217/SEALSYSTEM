<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Representa un equipo (o kiosko) en la base de datos.
 *
 * Un equipo está asociado a un usuario (`Users`) a través de `user_id` y a una licencia (`licencias`) a través de `licencia_id`.
 * Un equipo también puede tener múltiples listas (`listas`) asociadas a través de `equipo_id`.
 */

class equipos extends Model
{

    use HasFactory;

    protected $primaryKey = 'equipo_id'; // llave primaria

    /**
     * Campos asignables masivamente.
     *
     * @var array
     */
    protected $fillable = ['licencia_id', 'user_id', 'nombre', 'server_key', 'mac'];

    /**
     * Relación con la tabla 'users'.
     *
     * Un equipo pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuarios()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    /**
     * Relación con la tabla 'licencias'.
     *
     * Un equipo pertenece a una licencia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function licencias()
    {
        return $this->belongsTo(licencias::class, 'licencia_id');
    }

    /**
     * Relación con la tabla 'listas'.
     *
     * Un equipo puede tener muchas listas asociadas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function listas()
    {
        return $this->hasMany(listas::class, 'equipo_id');
    }

}