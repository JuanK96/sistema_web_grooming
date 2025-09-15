<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('created_at', 'desc')->take(5)->get();
        return view('cliente/gestion_cliente', compact('clientes'));
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        // Buscar clientes
        $clientes = Cliente::where('documento', 'like', "%{$search}%")
        ->orWhere('name','like' ,"%{$search}%")
        ->get();
        if($clientes->isNotEmpty()){
            return view('cliente/gestion_cliente', compact('clientes'));
        }else{
            return redirect()->route('gestioncliente')->with('error','No se encontraron resultados');
        }
    }
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
                'numeroDocumento.unique' => 'El cliente se encuentra registrado',
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
    public function update(Request $request)
    {
        // dd($request->all());
        // Validar datos recibidos
        $request->validate([
            'idcliente' => 'required|exists:clientes,id',
            'nombreCompleto' => 'required|string|max:255',
            'correoElectronico' => 'required|email',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'telefonoAlterno' => 'nullable|string|max:20',
        ]);
        // Buscar cliente por id
        $cliente = Cliente::findOrFail($request->idcliente);
        // Actualizar campos
        $cliente->update([
            'name' => $request->nombreCompleto,
            'email' => $request->correoElectronico,
            'direccion' => $request->direccion,
            'tel' => $request->telefono,
            'tel_dos' => $request->telefonoAlterno,
        ]);
        return redirect()->route('gestioncliente')->with('success', 'Cliente registrado correctamente');
    }
}
