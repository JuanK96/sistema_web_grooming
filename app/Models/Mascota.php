<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    protected $table = 'mascotas';

    protected $fillable = [
        'id_cliente',
        'name',
        'especie',
        'id_razarole',
        'peso',
        'fecha_nacimiento',
        'sexo',
        'esterilizado',
        'vacunado',
        'descripcion',
        'ruta_foto',
    ];
}
