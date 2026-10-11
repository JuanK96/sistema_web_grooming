<?php

namespace App\Services;

use App\Repositories\CitaRepositoryInterface;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\User;
use App\Events\CitaAgendadaEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CitaService
{
    protected $citaRepository;

    public function __construct(CitaRepositoryInterface $citaRepository)
    {
        $this->citaRepository = $citaRepository;
    }

    public function obtenerDatosIndex($userId, int $perPage)
    {
        $hoy = Carbon::today()->toDateString();
        $usuarios = User::where('id_role', 3)->where('active', 1)->select('id', 'name')->get();
        $citas = $this->citaRepository->obtenerCitasHoyPorUsuario($userId, $hoy, $perPage);

        return compact('citas', 'usuarios');
    }

    public function buscarCitas(string $search, int $perPage)
    {
        $usuarios = User::where('id_role', 3)->where('active', 1)->select('id', 'name')->get();
        $citas = $this->citaRepository->buscarCitas($search, $perPage);

        return compact('citas', 'usuarios');
    }

    public function agendarCita(array $data)
    {
        $horaInicio = Carbon::createFromFormat('g:i A', $data['hora_inicio'])->format('H:i');
        $horaFinal  = Carbon::createFromFormat('g:i A', $data['hora_final'])->format('H:i');

        $cliente = Cliente::where('documento', $data['dueño'])->first();
        if (!$cliente) {
            throw new \Exception('El cliente no está registrado.');
        }

        $nombreMascota = Mascota::where('id', $data['mascota'])->value('name');
        $groomerId = $data['grummer'];
        $fecha = $data['fecha'];

        if ($this->citaRepository->verificarTraslape($groomerId, $fecha, $horaInicio, $horaFinal)) {
            throw new \Exception('El horario seleccionado ya está ocupado.');
        }

        DB::beginTransaction();
        try {
            $cita = $this->citaRepository->guardar([
                'id_cliente' => $cliente->id,
                'id_mascota' => $data['mascota'],
                'id_user' => $groomerId,
                'fecha' => $fecha,
                'hora_inicio' => $horaInicio,
                'hora_final' => $horaFinal,
            ]);
            DB::commit();

            // Disparar evento Pub-Sub para notificación de WhatsApp
            event(new CitaAgendadaEvent($cita, $cliente, $nombreMascota));

            return $cita;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function actualizarEstadoCita($idCita, $userId, array $data)
    {
        $cita = $this->citaRepository->encontrarPorId($idCita);

        if ($userId != $cita->id_user) {
            throw new \Exception('El usuario no puede actualizar la cita');
        }

        if ($cita->created_at != $cita->updated_at) {
            throw new \Exception('Cita Ya Actualizada');
        }

        DB::beginTransaction();
        try {
            $citaActualizada = $this->citaRepository->actualizar($idCita, [
                'estado' => $data['estado'],
                'descripcion' => $data['description'],
            ]);
            DB::commit();
            return $citaActualizada;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function calcularHorasDisponibles($fecha, $groomer)
    {
        $inicioJornada = Carbon::createFromTimeString('08:30:00');
        $finJornada = Carbon::createFromTimeString('18:00:00');
        $intervalo = 30;

        $horasInicio = [];
        $horasFin = [];

        $hora = $inicioJornada->copy();
        while ($hora < $finJornada) {
            $horasInicio[$hora->format('g:i A')] = $hora->format('H:i:s');
            $hora->addMinutes($intervalo);
        }

        $hora = $inicioJornada->copy()->addMinutes($intervalo);
        while ($hora <= $finJornada) {
            $horasFin[$hora->format('g:i A')] = $hora->format('H:i:s');
            $hora->addMinutes($intervalo);
        }

        $citas = $this->citaRepository->obtenerCitasOcupadas($fecha, $groomer);

        $horasInicioDisponibles = array_filter(array_keys($horasInicio), function ($horaTexto) use ($horasInicio, $citas) {
            $hora = Carbon::createFromFormat('H:i:s', $horasInicio[$horaTexto]);
            return !$citas->contains(function ($cita) use ($hora) {
                return $hora->between(
                    Carbon::createFromFormat('H:i:s', $cita->hora_inicio),
                    Carbon::createFromFormat('H:i:s', $cita->hora_final)->subMinute()
                );
            });
        });

        $horasFinDisponibles = array_filter(array_keys($horasFin), function ($horaTexto) use ($horasFin, $citas) {
            $hora = Carbon::createFromFormat('H:i:s', $horasFin[$horaTexto]);
            return !$citas->contains(function ($cita) use ($hora) {
                return $hora->gt(Carbon::createFromFormat('H:i:s', $cita->hora_inicio)) &&
                    $hora->lt(Carbon::createFromFormat('H:i:s', $cita->hora_final));
            });
        });

        return [
            'inicio' => array_values($horasInicioDisponibles),
            'fin' => array_values($horasFinDisponibles),
        ];
    }
}
