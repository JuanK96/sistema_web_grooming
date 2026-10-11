<?php

namespace App\Repositories;

use App\Models\User;

class EloquentUsuarioRepository implements UsuarioRepositoryInterface
{
    public function obtenerUsuariosPaginados(int $perPage)
    {
        return User::with('role')->where('id', '>', 1)->orderBy('id', 'desc')->paginate($perPage);
    }

    public function buscarUsuarios(string $search, int $perPage)
    {
        return User::where('id', '!=', 1)
            ->where(function ($query) use ($search) {
                $query->where('email', 'like', "{$search}%")
                    ->orWhere('name', 'like', "{$search}%");
            })
            ->paginate($perPage);
    }

    public function guardar(array $data)
    {
        return User::create($data);
    }

    public function alternarEstado($usuario)
    {
        $usuario->active = !$usuario->active;
        $usuario->save();
        return $usuario;
    }
}