<?php

namespace App\Repositories;

interface CitaRepositoryInterface
{
    public function obtenerCitasHoyPorUsuario($userId, $hoy, int $perPage);
    public function buscarCitas(string $search, int $perPage);
    public function verificarTraslape($groomerId, $fecha, $horaInicio, $horaFinal);
    public function guardar(array $data);
    public function encontrarPorId($id);
    public function actualizar($id, array $data);
    public function obtenerCitasOcupadas($fecha, $groomerId);
}