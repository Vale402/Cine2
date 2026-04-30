<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Boleto extends Model
{
    protected $table = 'boletos';
    protected $fillable = ['funcion_id', 'asiento_id', 'user_id', 'precio', 'fecha_compra'];

    public function funcion()
    {
        return $this->belongsTo(Funcion::class, 'funcion_id');
    }

    public function asiento()
    {
        return $this->belongsTo(Asiento::class, 'asiento_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}