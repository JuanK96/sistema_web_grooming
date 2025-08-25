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
        'hora',
        'estado',
    ];
}
