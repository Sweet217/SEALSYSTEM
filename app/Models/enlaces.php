<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enlaces extends Model
{
    use HasFactory;

    protected $table = 'enlaces';
    protected $primaryKey = 'enlace_id';
    protected $fillable = ['data', 'multimedia_id'];

    public function multimedia()
    {
        return $this->belongsTo(Multimedia::class, 'multimedia_id', 'multimedia_id');
    }   
}