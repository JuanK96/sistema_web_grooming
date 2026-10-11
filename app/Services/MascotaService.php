<?php

namespace App\Services;

use App\Repositories\MascotaRepositoryInterface;
use App\Models\Cliente;
use App\Events\MascotaRegistradaEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MascotaService
{
    protected $mascotaRepository;

    public function __construct(MascotaRepositoryInterface $mascotaRepository)
    {
        $this->mascotaRepository = $mascotaRepository;
    }

    public function listarConEdadCalculada(int $perPage)
    {
        $mascotas = $this->mascotaRepository->obtenerPaginadosConDueño($perPage);
        return $this->calcularEdadMascotas($mascotas);
    }

    public function buscarConEdadCalculada(string $search)
    {
        $mascotas = $this->mascotaRepository->buscarPorTermino($search);
        return $this->calcularEdadMascotas($mascotas);
    }

    private function calcularEdadMascotas($mascotas)
    {
        foreach ($mascotas as $mascota) {
            $fechaNacimiento = Carbon::parse($mascota->fecha_nacimiento);
            $ahora = Carbon::now();
            $diasTotales = $fechaNacimiento->diffInDays($ahora);
            
            if ($diasTotales < 7) {
                $mascota->edad = "{$diasTotales} días";
            } elseif ($diasTotales < 365) {
                $semanas = round($diasTotales / 7);
                $mascota->edad = "{$semanas} semanas";
            } else {
                $añosEnteros = floor($diasTotales / 365.25);
                $diasRestantes = $diasTotales - ($añosEnteros * 365.25);
                $semanas = round($diasRestantes / 7);
                $mascota->edad = "{$añosEnteros} años y {$semanas} semanas";
            }
        }
        return $mascotas;
    }

    public function registrarMascota(array $data, $filePhoto)
    {
        DB::beginTransaction();
        try {
            $rutaFoto = null;
            if ($filePhoto) {
                $rutaFoto = $filePhoto->store('mascotas', 'public');
            }

            $idCliente = Cliente::where('documento', $data['dueño'])->value('id');

            $mascotaData = [
                'id_cliente' => $idCliente,
                'name' => $data['name'],
                'especie' => $data['especies'],
                'raza' => $data['raza'],
                'peso' => $data['weight'],
                'fecha_nacimiento' => $data['fechaNacimiento'],
                'sexo' => $data['sex'],
                'esterilizado' => $data['sterilized'] ?? 'NO',
                'vacunado' => $data['vaccinated'] ?? 'NO',
                'enfermedades' => $data['diseases'] ?? null,
                'descripcion' => $data['description'],
                'ruta_foto' => $rutaFoto,
            ];

            $mascota = $this->mascotaRepository->guardar($mascotaData);
            DB::commit();

            // Disparar evento Pub-Sub
            event(new MascotaRegistradaEvent($mascota));

            return $mascota;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function actualizarMascota($id, array $data, $filePhoto)
    {
        DB::beginTransaction();
        try {
            $mascota = $this->mascotaRepository->encontrarPorId($id);
            $rutaFoto = $mascota->ruta_foto;

            if ($filePhoto) {
                if ($rutaFoto && Storage::disk('public')->exists($rutaFoto)) {
                    Storage::disk('public')->delete($rutaFoto);
                }
                $rutaFoto = $filePhoto->store('mascotas', 'public');
            }

            $mascotaData = [
                'fecha_nacimiento' => $data['date'] ?? $mascota->fecha_nacimiento,
                'peso' => $data['weight'] ?? $mascota->peso,
                'esterilizado' => $data['sterilized'] ?? $mascota->esterilizado,
                'vacunado' => $data['vaccinated'] ?? $mascota->vacunado,
                'enfermedades' => $data['diseases'] ?? $mascota->enfermedades,
                'descripcion' => $data['description'] ?? $mascota->descripcion,
                'ruta_foto' => $rutaFoto,
            ];

            $mascotaActualizada = $this->mascotaRepository->actualizar($id, $mascotaData);
            DB::commit();
            return $mascotaActualizada;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}