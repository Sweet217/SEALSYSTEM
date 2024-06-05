<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;


class Users extends Model implements Authenticatable {
    
    
    use hasFactory, hasApiTokens, HasRoles;
    
    protected $table = 'usuarios';
    protected $primaryKey = 'user_id';

    protected $fillable = ['nombre','email', 'password', 'tipo_usuario', 'telefono', 'estado'];
    
    protected $hidden = ['password', 'remember_token'];

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'user_id');
    }
    
    public function getAuthIdentifier()
    {
        return $this->email;
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
        return 'email'; 
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