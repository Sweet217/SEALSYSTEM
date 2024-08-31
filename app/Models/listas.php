<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;

/**
 * Representa una lista en la base de datos.
 *
 * Una lista puede tener varios elementos multimedia (`multimedia`) asociados a través de `id_lista`.
 * Una lista también pertenece a un equipo (`equipos`) a través de `equipo_id`.
 */
class listas extends Model
{
    use HasFactory;

    protected $table = 'listas';
    protected $primaryKey = 'id_lista';
    /**
     * Campos asignables masivamente.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'id_lista', 'seleccionado'];

    /**
     * Relación con la tabla 'multimedia'.
     *
     * Una lista puede tener muchos elementos multimedia asociados.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function multimedia()
    {
        return $this->hasMany(multimedia::class, 'id_lista', 'id_lista');
    }
    /**
     * Relación con la tabla 'equipos'.
     *
     * Una lista pertenece a un equipo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipo()
    {
        return $this->belongsTo(equipos::class, 'equipo_id');
    }



}