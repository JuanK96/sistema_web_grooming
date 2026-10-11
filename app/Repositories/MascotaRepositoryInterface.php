<?php

namespace App\Repositories;

interface MascotaRepositoryInterface
{
    public function obtenerPaginadosConDueño(int $perPage);
    public function buscarPorTermino(string $search);
    public function guardar(array $data);
    public function encontrarPorId($id);
    public function actualizar($id, array $data);
}