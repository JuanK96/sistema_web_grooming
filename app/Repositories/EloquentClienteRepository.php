<?php

namespace App\Repositories;

use App\Models\Cliente;

class EloquentClienteRepository implements ClienteRepositoryInterface
{
    public function obtenerPaginados(int $perPage)
    {
        return Cliente::orderBy('id', 'desc')->paginate($perPage);
    }

    public function buscarPorTermino(string $search)
    {
        return Cliente::where('documento', 'like', "$search")
            ->orWhere('name', 'like', "{$search}%")
            ->orWhere('id', 'LIKE', "$search")
            ->paginate(5);
    }

    public function guardar(array $data)
    {
        return Cliente::create($data);
    }

    public function encontrarPorId($id)
    {
        return Cliente::findOrFail($id);
    }

    public function actualizar($id, array $data)
    {
        $cliente = $this->encontrarPorId($id);
        $cliente->update($data);
        return $cliente;
    }
}