<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';

    protected $fillable = [
        'id_cliente',
        'id_mascota',
        'id_user',
        'fecha',
        'hora_inicio',
        'hora_final',
        'estado',
        'descripcion',
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'id_mascota');
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
