<?php

namespace App\Repositories;

interface HistorialRepositoryInterface
{
    public function obtenerHistorialPaginado(int $perPage);
    public function buscarHistorial(string $search, int $perPage);
}