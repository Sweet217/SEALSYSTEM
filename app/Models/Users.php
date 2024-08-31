<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use App\Models\equipos;

/**
 * Representa un usuario en la base de datos.
 *
 * Un usuario del sistema con credenciales de acceso (email y contraseña) y roles asociados.
 * Puede tener tokens de API para la autenticación y gestionar múltiples equipos.
 */
class Users extends Model implements Authenticatable
{
    use HasFactory, HasApiTokens, HasRoles;

    protected $table = 'usuarios';
    protected $primaryKey = 'user_id';

    /**
     * Campos asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'tipo_usuario',
        'telefono',
        'estado',
    ];

    /**
     * Campos ocultos al serializar el modelo a JSON.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Relación con la tabla 'equipos'.
     *
     * Un usuario puede tener varios equipos asociados.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function equipos()
    {
        return $this->hasMany(Equipos::class, 'user_id');
    }

    // Implementations of Authenticatable interface methods (for Laravel authentication)

    public function getAuthIdentifier()
    {
        return $this->email;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    // Remember Me functionality is not currently implemented (null methods)

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        return null;
    }

    // Getter methods for compatibility with Laravel authentication

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