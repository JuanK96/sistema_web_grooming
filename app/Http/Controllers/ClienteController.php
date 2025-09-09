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

        return redirect()->route('gestioncliente')->with('success', 'Cliente registrado correctamente');
    }

    public function index()
    {
        $clientes = Cliente::orderBy('created_at', 'desc')->take(5)->get();
        return view('cliente/gestion_cliente', compact('clientes'));
    }

public function update(Request $request)
{
    // Validación (nota: aquí no se fuerza unique en documento/email, salvo que sea de otro cliente)
    $request->validate(
        [
            'id' => 'required|exists:clientes,id', // validar que el id exista
            'nombreCompleto' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'correoElectronico' => 'required|email',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
            'telefonoAlterno' => 'nullable|string|regex:/^\+?[0-9]{10,15}$/',
        ]
    );
    $cliente = Cliente::findOrFail($request->id);

    $cliente->name = $request->nombreCompleto;
    $cliente->email = $request->correoElectronico;
    $cliente->direccion = $request->direccion;
    $cliente->tel = $request->telefono;
    $cliente->tel_dos = $request->telefonoAlterno;
    $cliente->save();

    
    // Actualizar registro
    // $cliente->update([
    //     'name' => $request->nombreCompleto,
    //     'email' => $request->correoElectronico,
    //     'direccion' => $request->direccion,
    //     'tel' => $request->telefono,
    //     'tel_dos' => $request->telefonoAlterno,
    // ]);
    return redirect()->route('gestioncliente')->with('success', 'Cliente actualizado correctamente');
}
}