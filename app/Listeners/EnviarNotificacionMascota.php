<?php
namespace App\Listeners;
use App\Events\MascotaRegistradaEvent;
use Illuminate\Support\Facades\Log;

class EnviarNotificacionMascota {
    public function handle(MascotaRegistradaEvent $event) {
        Log::info("Nueva mascota registrada en el sistema: " . $event->mascota->name);
    }
}