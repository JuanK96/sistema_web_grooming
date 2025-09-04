<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function store(Request $request)
    {

        // Validación de los datos del formulario
        $request->validate(
            [
                'tipoDocumento' => 'required|string|max:255',
                'numeroDocumento' => 'required|numeric|unique:clientes,documento|digits_between:6,20',
                'nombreCompleto' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
                'fechaNacimiento' => 'required|date|before:today',
                'correoElectronico' => 'required|email',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
                'telefonoAlterno' => 'nullable|string|regex:/^\+?[0-9]{10,15}$/',
            ],
            [
                'tipoDocumento.required' => 'Por favor seleccione un tipo de documento.',
                'numeroDocumento.unique' => 'El cliente se encuentra registrado',
                'nombreCompleto.required' => 'Por favor ingrese su nombre completo.',
                'fechaNacimiento.required' => 'Por favor ingrese su fecha de nacimiento.',
                'correoElectronico.required' => 'Por favor ingrese un correo electrónico válido.',
                'direccion.required' => 'Por favor ingrese su dirección.',
                'telefono.required' => 'Por favor ingrese un número de teléfono.',
            ]
        );
        // Crear un nuevo registro
        Cliente::create([
            'tipo_documento' => $request->tipoDocumento,
            'documento' => $request->numeroDocumento,
            'name' => $request->nombreCompleto,
            'fecha_nacimiento' => $request->fechaNacimiento,
            'email' => $request->correoElectronico,
            'direccion' => $request->direccion,
            'tel' => $request->telefono,
            'tel_dos' => $request->telefonoAlterno,
        ]);
        // Redirigir con un mensaje de éxito
        return redirect()->route('gestioncliente');
    }

    public function index(){
        $clientes = Cliente::orderBy('created_at', 'desc')->take(5)->get();
        return view('cliente/gestion_cliente', compact('clientes'));
    }
}
