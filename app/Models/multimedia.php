<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class multimedia extends Model
{
    use HasFactory;

    protected $table = 'multimedia';
    protected $primaryKey = 'multimedia_id';
    protected $fillable = ['tipo', 'id_lista'];

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