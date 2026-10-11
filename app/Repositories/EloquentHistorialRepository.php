<?php

namespace App\Repositories;

use App\Models\Cita;

class EloquentHistorialRepository implements HistorialRepositoryInterface
{
    public function obtenerHistorialPaginado(int $perPage)
    {
        return Cita::with(['cliente:id,documento,name', 'mascota:id,name', 'usuario:id,name'])
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function buscarHistorial(string $search, int $perPage)
    {
        return Cita::with(['cliente:id,documento,name', 'mascota:id,name', 'usuario:id,name'])
            ->where(function ($query) use ($search) {
                $query->whereHas('mascota', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "{$search}%");
                })
                ->orWhereHas('cliente', function ($q) use ($search) {
                    $q->where('documento', 'LIKE', "{$search}%");
                })
                ->orWhereHas('usuario', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "{$search}%");
                })
                ->orWhere('id', 'LIKE', "{$search}%")
                ->orWhere('estado', 'LIKE', "{$search}%")
                ->orWhereDate('fecha', $search)
                ->orWhere('fecha', 'LIKE', "{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}