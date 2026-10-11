<?php
namespace App\Events;
use App\Models\Cliente;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClienteRegistradoEvent {
    use Dispatchable, SerializesModels;
    public $cliente;
    public function __construct(Cliente $cliente) {
        $this->cliente = $cliente;
    }
}