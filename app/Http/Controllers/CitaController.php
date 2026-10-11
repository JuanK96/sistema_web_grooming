<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\CitaService;
use App\Models\Cliente;
use App\Models\Mascota;

class CitaController extends Controller
{
    protected $citaService;

    // INYECCIÓN DE DEPENDENCIAS POR CONSTRUCTOR
    public function __construct(CitaService $citaService)
    {
        $this->citaService = $citaService;
    }

    public function index()
    {
        try {
            $data = $this->citaService->obtenerDatosIndex(Auth::id(), 5);
            return view('cita/gestion_cita', $data);
        } catch (\Exception $e) {
            return redirect()->route('gestioncita')->with('error', 'Error al cargar las citas');
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        if (empty($search)) {
            return redirect()->route('gestioncita')->with('error', 'Ingresa un término de búsqueda');
        }

        try {
            $data = $this->citaService->buscarCitas($search, 5);
            if ($data['citas']->isNotEmpty()) {
                return view('cita/gestion_cita', $data);
            } else {
                return redirect()->route('gestioncita')->with('error', 'No se encontraron resultados');
            }
        } catch (\Exception $e) {
            return redirect()->route('gestioncita')->with('error', 'Error en la busqueda');
        }
    }

    public function obtenerMascotas(Request $request)
    {
        $request->validate(['dueño' => 'required|string']);
        try {
            $cliente = Cliente::where('documento', $request->input('dueño'))->first();
            if (!$cliente) {
                return response()->json([], 200);
            }
            $mascotas = Mascota::where('id_cliente', $cliente->id)->get();
            return response()->json($mascotas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener mascotas'], 500);
        }
    }

    public function agendar(Request $request)
    {
        $request->validate([
            'dueño' => 'required|string|max:255',
            'mascota' => 'required|string|max:255',
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora_inicio' => 'required',
            'hora_final' => 'required',
            'grummer' => 'required',
        ]);

        try {
            $this->citaService->agendarCita($request->all());
            return redirect()->route('gestioncita')->with('success', 'Cita agendada correctamente');
        } catch (\Exception $e) {
            return redirect()->route('gestioncita')->with('error', $e->getMessage() ?: 'Ocurrió un error al registrar la cita');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'idcita' => 'required|exists:citas,id',
            'estado' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            $this->citaService->actualizarEstadoCita($request->idcita, Auth::id(), $request->all());
            return redirect()->route('gestioncita')->with('success', 'Estado de cita Actualizado');
        } catch (\Exception $e) {
            return redirect()->route('gestioncita')->with('error', $e->getMessage() ?: 'Error al actualizar cita');
        }
    }

    public function horasDisponibles(Request $request)
    {
        try {
            $resultado = $this->citaService->calcularHorasDisponibles(
                $request->input('fecha'),
                $request->input('grummer')
            );
            return response()->json($resultado);
        } catch (\Exception $e) {
            Log::error('Error en horasDisponibles: ' . $e->getMessage());
            return response()->json(['error' => 'Error al consultar horas disponibles'], 500);
        }
    }
}