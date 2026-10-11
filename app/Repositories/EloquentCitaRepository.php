<?php

namespace App\Repositories;

use App\Models\Cita;

class EloquentCitaRepository implements CitaRepositoryInterface
{
    public function obtenerCitasHoyPorUsuario($userId, $hoy, int $perPage)
    {
        return Cita::where('id_user', $userId)
            ->with(['cliente:id,documento,name', 'mascota:id,name', 'usuario:id,name'])
            ->whereDate('fecha', $hoy)
            ->orderBy('hora_inicio', 'asc')
            ->paginate($perPage);
    }

    public function buscarCitas(string $search, int $perPage)
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
                ->orWhere('id', 'LIKE', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function verificarTraslape($groomerId, $fecha, $horaInicio, $horaFinal)
    {
        return Cita::where('id_user', $groomerId)
            ->where('fecha', $fecha)
            ->where(function ($query) use ($horaInicio, $horaFinal) {
                $query->where('hora_inicio', '<', $horaFinal)
                      ->where('hora_final', '>', $horaInicio);
            })
            ->exists();
    }

    public function guardar(array $data)
    {
        return Cita::create($data);
    }

    public function encontrarPorId($id)
    {
        return Cita::findOrFail($id);
    }

    public function actualizar($id, array $data)
    {
        $cita = $this->encontrarPorId($id);
        $cita->update($data);
        return $cita;
    }

    public function obtenerCitasOcupadas($fecha, $groomerId)
    {
        return Cita::where('fecha', $fecha)
            ->where('id_user', $groomerId)
            ->where('estado', 'PENDIENTE')
            ->get(['hora_inicio', 'hora_final']);
    }
}