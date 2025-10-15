<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index()
    {
        try {
            $clientes = Cliente::orderBy('id', 'desc')->paginate(4);
            return view('cliente/gestion_cliente', compact('clientes'));
        } catch (\Exception $e) {
            // Aquí puedes registrar el error o mostrar un mensaje personalizado
            return redirect()->route('gestioncliente')->with('error', 'Error al cargar clientes');
        }
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        if (empty($search)) {
            return redirect()->route('gestioncliente')->with('error', 'Ingresa un término de búsqueda');
        } else {
            try {
                // Buscar clientes
                $clientes = Cliente::where('documento', 'like', "$search")
                    ->orWhere('name', 'like', "{$search}%")
                    ->orWhere('id', 'LIKE', "$search")
                    ->paginate(5)
                    ->appends(['search' => $search]);
                if ($clientes->isNotEmpty()) {
                    return view('cliente/gestion_cliente', compact('clientes'));
                } else {
                    return redirect()->route('gestioncliente')->with('error', 'No se encontraron resultados');
                }
            } catch (\Exception $e) {
                // Aquí puedes registrar el error o mostrar un mensaje personalizado
                return redirect()->route('gestioncliente')->with('error', 'Error en la busqueda');
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
        try {
            // Crear un nuevo registro
            DB::beginTransaction();
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
            DB::commit();
            return redirect()->route('gestioncliente')->with('success', 'Cliente registrado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('gestioncliente')->with('error', 'Error al registrar el cliente');
        }
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
        try {
            $cliente = Cliente::findOrFail($request->idcliente);
            // Actualizar campos
            DB::beginTransaction();
            $cliente->update([
                'name' => $request->nombreCompleto,
                'email' => $request->correoElectronico,
                'direccion' => $request->direccion,
                'tel' => $request->telefono,
                'tel_dos' => $request->telefonoAlterno,
            ]);
            DB::commit();
            return redirect()->route('gestioncliente')->with('success', 'Cliente actualizado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('gestioncliente')->with('error', 'Error al actualizar el cliente');
        }
    }
}
