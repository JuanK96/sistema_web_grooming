<?php

namespace App\Repositories;

use App\Models\Mascota;

class EloquentMascotaRepository implements MascotaRepositoryInterface
{
    public function obtenerPaginadosConDueño(int $perPage)
    {
        return Mascota::with('dueño:id,documento')->orderBy('id', 'desc')->paginate($perPage);
    }

    public function buscarPorTermino(string $search)
    {
        return Mascota::with('dueño:id,documento')
            ->whereHas('dueño', function ($query) use ($search) {
                $query->where('documento', 'like', "$search");
            })
            ->orWhere('name', 'like', "{$search}%")
            ->orWhere('id', 'LIKE', "$search")
            ->paginate(5);
    }

    public function guardar(array $data)
    {
        return Mascota::create($data);
    }

    public function encontrarPorId($id)
    {
        return Mascota::findOrFail($id);
    }

    public function actualizar($id, array $data)
    {
        $mascota = $this->encontrarPorId($id);
        $mascota->update($data);
        return $mascota;
    }
}