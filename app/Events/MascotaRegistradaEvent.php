<?php
namespace App\Events;
use App\Models\Mascota;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MascotaRegistradaEvent {
    use Dispatchable, SerializesModels;
    public $mascota;
    public function __construct(Mascota $mascota) {
        $this->mascota = $mascota;
    }
}