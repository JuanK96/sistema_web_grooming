<?php
namespace App\Events;
use App\Models\Cita;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CitaAgendadaEvent {
    use Dispatchable, SerializesModels;
    public $cita;
    public $cliente;
    public $nombreMascota;

    public function __construct(Cita $cita, $cliente, $nombreMascota) {
        $this->cita = $cita;
        $this->cliente = $cliente;
        $this->nombreMascota = $nombreMascota;
    }
}