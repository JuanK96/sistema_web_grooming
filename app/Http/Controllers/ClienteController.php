<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('created_at', 'desc')->paginate(4);
        return view('cliente/gestion_cliente', compact('clientes'));
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        if (empty($search)) {
            return redirect()->route('gestioncliente')->with('error', 'Ingresa un término de búsqueda');
        } else {
            // Buscar clientes
            $clientes = Cliente::where('documento', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->paginate(5)
                ->appends(['search' => $search]);
            if ($clientes->isNotEmpty()) {
                return view('cliente/gestion_cliente', compact('clientes'));
            } else {
                return redirect()->route('gestioncliente')->with('error', 'No se encontraron resultados');
            }
        }
    }
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate(
            [
                'tipoDocumento' => 'required|string|max:255',
                'numeroDocumento' => 'required|string|unique:clientes,documento',
                'nombreCompleto' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
                'fechaNacimiento' => 'required|date|before:today',
                'correoElectronico' => 'required|email',
                'direccion' => 'required|string|max:255',
                'extension' => 'required|string',
                'telefono' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
                'extTelAlt' => 'nullable|string',
                'telefonoAlterno' => 'nullable|string|regex:/^\+?[0-9]{10,15}$/',
            ],
            [
                'numeroDocumento.unique' => 'El cliente se encuentra registrado',
            ]
        );
        $telefonoCompleto = $request->extension . $request->telefono;
        $telAltCompleto = $request->extTelAlt . $request->telefonoAlterno;
        // Crear un nuevo registro
        Cliente::create([
            'tipo_documento' => $request->tipoDocumento,
            'documento' => $request->numeroDocumento,
            'name' => $request->nombreCompleto,
            'fecha_nacimiento' => $request->fechaNacimiento,
            'email' => $request->correoElectronico,
            'direccion' => $request->direccion,
            'tel' => $telefonoCompleto,
            'tel_dos' => $telAltCompleto,
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
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string',
            'telefonoAlterno' => 'nullable|string',
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
