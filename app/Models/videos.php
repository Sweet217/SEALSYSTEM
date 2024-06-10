<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class videos extends Model
{
    use HasFactory;

    protected $table = 'videos';
    protected $primaryKey = 'video_id';
    protected $fillable = ['nombre_archivo', 'data', 'multimedia_id'];

    public function multimedia()
    {
        return $this->belongsTo(Multimedia::class, 'multimedia_id', 'multimedia_id');
    }
}