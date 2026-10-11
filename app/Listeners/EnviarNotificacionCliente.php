<?php
namespace App\Listeners;
use App\Events\ClienteRegistradoEvent;
use Illuminate\Support\Facades\Log;

class EnviarNotificacionCliente {
    public function handle(ClienteRegistradoEvent $event) {
        // Simulación de acción desacoplada (ej. enviar correo de bienvenida o registrar log)
        Log::info("Notificación enviada al nuevo cliente: " . $event->cliente->email);
    }
}