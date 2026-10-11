<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function enviarMensaje($telefono, $mensaje)
    {
        $instance = env('ULTRAMSG_INSTANCE');
        $token = env('ULTRAMSG_TOKEN');
        $telefono = ltrim($telefono, '+');
        $url = "https://api.ultramsg.com/$instance/messages/chat";

        try {
            $response = Http::post($url, [
                'token' => $token,
                'to' => $telefono,
                'body' => $mensaje,
            ]);

            if ($response->successful()) {
                return true;
            } else {
                Log::error("Error UltraMsg: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Error enviando WhatsApp UltraMsg: " . $e->getMessage());
            return false;
        }
    }
}