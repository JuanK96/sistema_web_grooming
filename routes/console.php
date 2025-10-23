<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Cita;
use App\Models\Cliente;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $mañana = Carbon::tomorrow()->toDateString();
    $citas = Cita::with(['cliente', 'mascota'])
        ->whereDate('fecha', $mañana)
        ->get();
    foreach ($citas as $cita) {
        if (!$cita->cliente || !$cita->cliente->tel) continue;
        $fecha = Carbon::parse($cita->fecha)->format('d/m/Y');
        $hora = Carbon::parse($cita->hora_inicio)->format('g:i A');
        $telefono = $cita->cliente->tel;
        $primerNombre = explode(' ', trim($cita->cliente->name))[0];
        $mensaje = "Hola {$primerNombre}, te recordamos la cita de {$cita->mascota->name} el {$fecha} a las {$hora}. ¡Te esperamos! 🐾";
        app(\App\Http\Controllers\CitaController::class)->enviarConfirmacionWhatsApp(
            $telefono,
            $mensaje
        );
    }
})->dailyAt('19:15');
// Invitaciones cada 1 y 15 del mes
Schedule::call(function () {
    // $hoy = Carbon::today();
    $hace20dias = Carbon::now()->subDays(20);
    // Clientes sin cita en los últimos 20 días
    $clientes = Cliente::whereDoesntHave('citas', function ($q) use ($hace20dias) {
        $q->where('fecha', '>=', $hace20dias);
    })->get();
    foreach ($clientes as $cliente) {
        if (!$cliente->tel) continue;
        $primerNombre = explode(' ', trim($cliente->name))[0];
        $telefono = $cliente->tel;
        $mensaje = "Hola {$primerNombre} 👋, hace un tiempo que no traes a tu peludito 🐶🐱. 
                    Agenda tu próxima cita para baño o peluquería, y consiente a tu mascota 💜
                    Reserva fácil aquí, escríbenos";
        app(\App\Http\Controllers\CitaController::class)->enviarConfirmacionWhatsApp(
            $telefono,
            $mensaje
        );
    }
})->twiceMonthly(1, 15)->at('09:00'); // Días 1 y 15 del mes a las 9:00 a.m.
