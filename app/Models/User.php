<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'rol'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function boletos()
    {
        return $this->hasMany(Boleto::class);
    }

    public function esAdministrador()
    {
        return $this->rol === 'administrador';
    }

    public function esCliente()
    {
        return $this->rol === 'cliente';
    }
}