<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Spatie\Permission\Traits\HasRoles;


class Users extends Model implements Authenticatable {
    
    use HasRoles;
    
    protected $table = 'usuarios';
    protected $primaryKey = 'usuario_id';

    protected $fillable = ['correo', 'password'];
    
    protected $hidden = ['password'];
    
    public function getAuthIdentifier()
    {
        return $this->correo;
    }

    public function getAuthPassword()
    {
        return $this->password; 
    }

    public function getRememberToken()
    {
        return null; 
    }

    public function setRememberToken($value)
    {
        return null;
    }

    public function getAuthIdentifierName()
    {
        return 'correo'; 
    }

    public function getAuthPasswordName()
    {
        return 'password'; 
    }

    public function getRememberTokenName()
    {
        return null; 
    }
}