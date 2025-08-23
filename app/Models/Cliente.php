<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'tipo_documento',
        'documento',
        'name',
        'fecha_nacimiento',
        'tel',
        'tel_dos',
        'direccion',
        'email',
    ];
}
