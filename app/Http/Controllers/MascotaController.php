<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Mascota;

class MascotaController extends Controller
{
    public function store(Request $request)
    {

        // Validación de los datos del formulario
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'dueño' => 'required|string|unique:clientes,documento|digits_between:6,20',
                'especies' => 'required|string|max:255|',
                'raza' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
                'fechaNacimiento' => 'required|date|before:today',
                'weight'=> 'required|string|max:255',
                'sex' => 'required|string|max:255',
                'sterilized' => 'required|string|max:255',
                'vaccinated' => 'required|string|max:255',
                'diseases' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'photo' => 'nullable|string|max:255',

            ],
            [
                'name.required' => 'Por favor digite el nombre.',
                'numeroDocumento.unique' => 'El cliente se encuentra registrado',
                'nombreCompleto.required' => 'Por favor ingrese su nombre completo.',
                'fechaNacimiento.required' => 'Por favor ingrese su fecha de nacimiento.',
                'correoElectronico.required' => 'Por favor ingrese un correo electrónico válido.',
                'direccion.required' => 'Por favor ingrese su dirección.',
                'telefono.required' => 'Por favor ingrese un número de teléfono.',
            ]
        );
        // Crear un nuevo registro
        Mascota::create([
            'id_cliente' => Cliente::where('documento',$request->dueño)->first('id'),
            'name' => $request->name,
            'especie' => $request->especies,
            'raza' => $request->raza,
            'peso' => $request->weight,
            'fecha_nacimiento' => $request->fechaNacimiento,
            'sexo' => $request->sex,
            'esterilizado' => $request->sterilized,
            'enfermedades'=> $request->diseases,
            'vacunado' => $request->vaccinated,
            'descripcion' => $request->description,
            'ruta_foto' => $request->photo,
            
        ]);
        // Redirigir con un mensaje de éxito
        return redirect()->route('gestionmascota');
    }
}

