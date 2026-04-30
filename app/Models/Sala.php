<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    protected $table = 'salas';
    protected $fillable = ['nombre', 'capacidad', 'tipo', 'precio'];

    public function asientos()
    {
        return $this->hasMany(Asiento::class, 'sala_id');
    }

    public function funciones()
    {
        return $this->hasMany(Funcion::class, 'sala_id');
    }
}
