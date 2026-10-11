<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClienteService;

class ClienteController extends Controller
{
    protected $clienteService;

    // INYECCIÓN DE DEPENDENCIAS DEL SERVICIO POR CONSTRUCTOR
    public function __construct(ClienteService $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    public function index()
    {
        try {
            $clientes = $this->clienteService->listarClientes(4);
            return view('cliente/gestion_cliente', compact('clientes'));
        } catch (\Exception $e) {
            return redirect()->route('gestioncliente')->with('error', 'Error al cargar clientes');
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        if (empty($search)) {
            return redirect()->route('gestioncliente')->with('error', 'Ingresa un término de búsqueda');
        }

        try {
            $clientes = $this->clienteService->buscarClientes($search);
            if ($clientes->isNotEmpty()) {
                return view('cliente/gestion_cliente', compact('clientes'));
            } else {
                return redirect()->route('gestioncliente')->with('error', 'No se encontraron resultados');
            }
        } catch (\Exception $e) {
            return redirect()->route('gestioncliente')->with('error', 'Error en la busqueda');
        }
    }

    public function store(Request $request)
    {
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

        try {
            $this->clienteService->registrarCliente($request->all());
            return redirect()->route('gestioncliente')->with('success', 'Cliente registrado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('gestioncliente')->with('error', 'Error al registrar el cliente');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'idcliente' => 'required|exists:clientes,id',
            'nombreCompleto' => 'required|string|max:255',
            'date' => 'date|before:today',
            'correoElectronico' => 'required|email',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string',
            'telefonoAlterno' => 'nullable|string',
        ]);

        try {
            $this->clienteService->actualizarCliente($request->idcliente, $request->all());
            return redirect()->route('gestioncliente')->with('success', 'Cliente actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('gestioncliente')->with('error', 'Error al actualizar el cliente');
        }
    }
}