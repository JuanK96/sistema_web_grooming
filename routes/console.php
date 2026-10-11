<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Cita;
use App\Models\Cliente;
use App\Services\WhatsAppService;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Recordatorio de citas para el día siguiente (11:00 AM)
Schedule::call(function () {
    $mañana = Carbon::tomorrow()->toDateString();
    $citas = Cita::with(['cliente', 'mascota'])
        ->whereDate('fecha', $mañana)
        ->get();

    $whatsAppService = new WhatsAppService();

    foreach ($citas as $cita) {
        if (!$cita->cliente || !$cita->cliente->tel) continue;
        
        $fecha = Carbon::parse($cita->fecha)->format('d/m/Y');
        $hora = Carbon::parse($cita->hora_inicio)->format('g:i A');
        $telefono = $cita->cliente->tel;
        $primerNombre = explode(' ', trim($cita->cliente->name))[0];
        
        $mensaje = "¡Hola! {$primerNombre} 🐾
Te saludamos desde Caprichos Pet Grooming sede Querencia.

Queremos recordarte que tienes programada la cita de grooming para {$cita->mascota->name}.
Por favor, confírmanos si podrás asistir el {$fecha} a las {$hora}, así garantizamos un servicio fluido y sin retrasos para tu mascota.

Gracias por tu puntualidad y por confiar en nosotros 🐶✨
Quedamos atentos a tu confirmación.";

        $whatsAppService->enviarMensaje($telefono, $mensaje);
    }
})->dailyAt('11:00');

// Invitaciones a clientes sin cita en los últimos 20 días (Días 1 y 15 del mes a la 1:00 PM)
Schedule::call(function () {
    $hace20dias = Carbon::now()->subDays(20);
    
    $clientes = Cliente::whereDoesntHave('citas', function ($q) use ($hace20dias) {
        $q->where('fecha', '>=', $hace20dias);
    })->get();

    $whatsAppService = new WhatsAppService();

    foreach ($clientes as $cliente) {
        if (!$cliente->tel) continue;
        
        $primerNombre = explode(' ', trim($cliente->name))[0];
        $telefono = $cliente->tel;
        
        $mensaje = "¡Hola! {$primerNombre} 🐾
Esperamos que tú y tu peludito se encuentren muy bien.

Te saludamos desde Caprichos Pet Grooming sede Querencia, con todo el cariño de siempre.
Queríamos saber si te gustaría agendar nuevamente la cita de baño para tu mascota, ya pasó un tiempo desde su última visita con nosotros. Nos encantará volver a consentirl@ como se merece.

También nos gustaría saber cómo te fue con el último servicio.
¿Hay algo que podamos mejorar o alguna recomendación que quieras compartirnos? Tu opinión nos ayuda muchísimo a seguir brindando la mejor experiencia.

Quedamos muy atentos a tu mensaje 🐶❤️🐾";

        $whatsAppService->enviarMensaje($telefono, $mensaje);
    }
})->twiceMonthly(1, 15)->at('13:00');