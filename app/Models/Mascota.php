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
        'raza',
        'peso',
        'fecha_nacimiento',
        'sexo',
        'esterilizado',
        'vacunado',
        'enfermedades',
        'descripcion',
        'ruta_foto',
    ];
    public function dueño()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}
