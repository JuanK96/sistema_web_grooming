<?php

namespace App\Services;

use App\Repositories\UsuarioRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioService
{
    protected $usuarioRepository;

    public function __construct(UsuarioRepositoryInterface $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
    }

    public function listarUsuarios(int $perPage)
    {
        return $this->usuarioRepository->obtenerUsuariosPaginados($perPage);
    }

    public function buscarUsuarios(string $search, int $perPage)
    {
        return $this->usuarioRepository->buscarUsuarios($search, $perPage);
    }

    public function registrarUsuario(array $data)
    {
        DB::beginTransaction();
        try {
            $usuario = $this->usuarioRepository->guardar([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'id_role' => $data['role'],
            ]);
            DB::commit();
            return $usuario;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cambiarEstado($usuario)
    {
        DB::beginTransaction();
        try {
            $usuarioActualizado = $this->usuarioRepository->alternarEstado($usuario);
            DB::commit();
            return $usuarioActualizado;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}