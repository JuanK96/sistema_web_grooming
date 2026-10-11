<?php

namespace App\Services;

use App\Repositories\HistorialRepositoryInterface;
use App\Models\User;
use App\Exports\CitasExport;
use Maatwebsite\Excel\Facades\Excel;

class HistorialService
{
    protected $historialRepository;

    public function __construct(HistorialRepositoryInterface $historialRepository)
    {
        $this->historialRepository = $historialRepository;
    }

    public function obtenerDatosIndex(int $perPage)
    {
        $usuarios = User::where('id_role', 3)->where('active', 1)->select('id', 'name')->get();
        $citas = $this->historialRepository->obtenerHistorialPaginado($perPage);

        return compact('citas', 'usuarios');
    }

    public function buscarHistorial(string $search, int $perPage)
    {
        $usuarios = User::where('id_role', 3)->where('active', 1)->select('id', 'name')->get();
        $citas = $this->historialRepository->buscarHistorial($search, $perPage);

        return compact('citas', 'usuarios');
    }

    public function generarReporteExcel(array $filtros)
    {
        $nombreArchivo = 'reporte_citas_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        
        return Excel::download(
            new CitasExport(
                $filtros['fecha_inicio'] ?? null,
                $filtros['fecha_fin'] ?? null,
                $filtros['estado'] ?? null,
                $filtros['usuario_id'] ?? null,
                $filtros['cliente'] ?? null
            ), 
            $nombreArchivo
        );
    }
}