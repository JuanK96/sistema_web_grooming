<?php

namespace App\Services;

use App\Repositories\ClienteRepositoryInterface;
use App\Events\ClienteRegistradoEvent;
use Illuminate\Support\Facades\DB;

class ClienteService
{
    protected $clienteRepository;

    // INYECCIÓN DE DEPENDENCIAS DEL REPOSITORIO
    public function __construct(ClienteRepositoryInterface $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function listarClientes(int $perPage)
    {
        return $this->clienteRepository->obtenerPaginados($perPage);
    }

    public function buscarClientes(string $search)
    {
        return $this->clienteRepository->buscarPorTermino($search);
    }

    public function registrarCliente(array $data)
    {
        DB::beginTransaction();
        try {
            $telefonoCompleto = $data['extension'] . $data['telefono'];
            $telAltCompleto = ($data['extTelAlt'] ?? '') . ($data['telefonoAlterno'] ?? '');

            $clienteData = [
                'tipo_documento' => $data['tipoDocumento'],
                'documento' => $data['numeroDocumento'],
                'name' => $data['nombreCompleto'],
                'fecha_nacimiento' => $data['fechaNacimiento'],
                'email' => $data['correoElectronico'],
                'direccion' => $data['direccion'],
                'tel' => $telefonoCompleto,
                'tel_dos' => $telAltCompleto,
            ];

            $cliente = $this->clienteRepository->guardar($clienteData);
            
            DB::commit();

            // PATRÓN PUBLICADOR-SUSCRIPTOR: Disparamos el evento de forma desacoplada
            event(new ClienteRegistradoEvent($cliente));

            return $cliente;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function actualizarCliente($id, array $data)
    {
        DB::beginTransaction();
        try {
            $clienteData = [
                'name' => $data['nombreCompleto'],
                'fecha_nacimiento' => $data['date'],
                'email' => $data['correoElectronico'],
                'direccion' => $data['direccion'],
                'tel' => $data['telefono'],
                'tel_dos' => $data['telefonoAlterno'] ?? null,
            ];

            $cliente = $this->clienteRepository->actualizar($id, $clienteData);
            DB::commit();
            return $cliente;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}