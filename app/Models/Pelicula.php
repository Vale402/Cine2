<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    protected $table = 'peliculas';
    protected $fillable = ['titulo', 'genero', 'duracion', 'clasificacion', 'idioma'];

    public function funciones()
    {
        return $this->hasMany(Funcion::class, 'pelicula_id');
    }
}
