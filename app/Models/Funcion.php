<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Funcion extends Model
{
    protected $table = 'funciones';
    protected $fillable = ['pelicula_id', 'sala_id', 'fecha', 'hora'];

    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class, 'pelicula_id');
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }

    public function boletos()
    {
        return $this->hasMany(Boleto::class, 'funcion_id');
    }
}