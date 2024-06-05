<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;

class listas extends Model
{
    use HasFactory;

    protected $table = 'listas';
    protected $primaryKey = 'id_lista';
    
    protected $fillable = ['nombre', 'id_lista'];
    
}