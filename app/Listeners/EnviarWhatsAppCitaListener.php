<?php

namespace App\Listeners;

use App\Events\CitaAgendadaEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EnviarWhatsAppCitaListener
{
    public function handle(CitaAgendadaEvent $event)
    {
        $cliente = $event->cliente;
        if (empty($cliente->tel)) return;

        $instance = env('ULTRAMSG_INSTANCE');
        $token = env('ULTRAMSG_TOKEN');
        $telefono = ltrim($cliente->tel, '+');
        $url = "https://api.ultramsg.com/$instance/messages/chat";

        $primerNombre = explode(' ', trim($cliente->name))[0];
        $fecha = Carbon::parse($event->cita->fecha)->format('d/m/Y');
        $horaInicio = Carbon::parse($event->cita->hora_inicio)->format('g:i A');
        $mascota = $event->nombreMascota;

        $mensaje = "Hola {$primerNombre}, tu cita para {$mascota} está confirmada para el {$fecha} a las {$horaInicio}.✂️🐶
‼️ TEN EN CUENTA ‼️
                
• Llegadas superiores a 15 minutos después de la hora acordada, serán consideradas como no presentación y no podremos atenderte 🚫⏱️
                
• En caso de no presentarte, habrá restricción para reagendar de inmediato, ya que es necesario organizar los turnos 📅
                
• El valor del servicio puede variar según el estado del pelo y el comportamiento de tu mascota 🐾✂️😊
                
• Una hora después del llamado, se empieza a cobrar guardería con un valor de $10.000 por hora 🩵🐕
                
😊 ¡Te esperamos! ❤️🐶🐾";
        try {
            $response = Http::post($url, [
                'token' => $token,
                'to' => $telefono,
                'body' => $mensaje,
            ]);
            if (!$response->successful()) {
                Log::error("Error Ultramsg: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Error enviando WhatsApp Ultramsg: " . $e->getMessage());
        }
    }
}
