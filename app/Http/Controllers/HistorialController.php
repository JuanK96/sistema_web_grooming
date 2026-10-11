<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HistorialService;

class HistorialController extends Controller
{
    protected $historialService;

    // INYECCIÓN DE DEPENDENCIAS POR CONSTRUCTOR
    public function __construct(HistorialService $historialService)
    {
        $this->historialService = $historialService;
    }

    public function index()
    {
        try {
            $data = $this->historialService->obtenerDatosIndex(5);
            return view('cita/gestion_historial_cita', $data);
        } catch (\Exception $e) {
            return redirect()->route('gestionhistorial')->with('error', 'Error al cargar las citas');
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        if (empty($search)) {
            return redirect()->route('gestionhistorial')->with('error', 'Ingresa un término de búsqueda');
        }

        try {
            $data = $this->historialService->buscarHistorial($search, 5);
            if ($data['citas']->isNotEmpty()) {
                return view('cita/gestion_historial_cita', $data);
            } else {
                return redirect()->route('gestionhistorial')->with('error', 'No se encontraron resultados');
            }
        } catch (\Exception $e) {
            return redirect()->route('gestionhistorial')->with('error', 'Error en la busqueda');
        }
    }

    public function reporte(Request $request)
    {
        try {
            return $this->historialService->generarReporteExcel($request->all());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al generar el reporte');
        }
    }
}