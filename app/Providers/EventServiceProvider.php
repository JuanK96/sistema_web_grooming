<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// Importar nuestros eventos y listeners personalizados
use App\Events\ClienteRegistradoEvent;
use App\Listeners\EnviarNotificacionCliente;

use App\Events\MascotaRegistradaEvent;
use App\Listeners\EnviarNotificacionMascota;

use App\Events\CitaAgendadaEvent;
use App\Listeners\EnviarWhatsAppCitaListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // 1. Evento de Clientes
        ClienteRegistradoEvent::class => [
            EnviarNotificacionCliente::class,
        ],

        // 2. Evento de Mascotas
        MascotaRegistradaEvent::class => [
            EnviarNotificacionMascota::class,
        ],

        // 3. Evento de Citas (WhatsApp)
        CitaAgendadaEvent::class => [
            EnviarWhatsAppCitaListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    shouldDiscoverEvents(): bool
    {
        return false;
    }
}