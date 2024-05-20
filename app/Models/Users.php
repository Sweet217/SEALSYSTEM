<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class Users extends Model implements Authenticatable
{
    protected $table = 'usuarios';
    protected $primaryKey = 'usuario_id';

    public function getAuthIdentifier()
    {
        return $this->usuario_id;
    }

    public function getAuthPassword()
    {
        return $this->contrasena; 
    }

    public function getRememberToken()
    {
        return null; 
    }

    public function setRememberToken($value)
    {
        
    }

    public function getAuthIdentifierName()
    {
        return 'usuario_id'; 
    }

    public function getAuthPasswordName()
    {
        return 'contrasena'; 
    }

    public function getRememberTokenName()
    {
        return null; 
    }
}