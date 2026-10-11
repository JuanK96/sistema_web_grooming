<?php

namespace App\Repositories;

interface UsuarioRepositoryInterface
{
    public function obtenerUsuariosPaginados(int $perPage);
    public function buscarUsuarios(string $search, int $perPage);
    public function guardar(array $data);
    public function alternarEstado($usuario);
}