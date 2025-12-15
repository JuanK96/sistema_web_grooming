<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\Mascota;

class ImportClientesMascotasSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/import/clientes_mascotas.csv');

        if (!file_exists($path)) {
            $this->command->error('Archivo CSV no encontrado');
            return;
        }

        DB::transaction(function () use ($path) {

            $file = fopen($path, 'r');
            
            $headers = fgetcsv($file, 0, ';');

            // eliminar BOM del primer header
            $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);

            // normalizar headers
            $headers = array_map(fn($h) => trim(strtolower($h)), $headers);


            while (($row = fgetcsv($file, 0, ';')) !== false) {

                if (count($row) !== count($headers)) {
                    continue; // evita filas dañadas
                }

                $data = array_combine($headers, $row);

                // CLIENTE
                $cliente = Cliente::updateOrCreate(
                    ['documento' => trim($data['documento'])],
                    [
                        'tipo_documento'    => trim($data['tipo_documento']),
                        'name'              => strtoupper(trim($data['nombre_cliente'])),
                        'fecha_nacimiento'  => $data['fecha_nacimiento_cliente'],
                        'tel'               => trim($data['tel']),
                        'tel_dos'           => $data['tel_dos'] ?: null,
                        'direccion'         => strtoupper(trim($data['direccion'])),
                        'email'             => strtolower(trim($data['email'])),
                    ]
                );

                // MASCOTA
                Mascota::create([
                    'id_cliente'       => $cliente->id,
                    'name'             => strtoupper(trim($data['nombre_mascota'])),
                    'especie'          => strtoupper(trim($data['especie'])),
                    'raza'             => strtoupper(trim($data['raza'])),
                    'peso'             => trim($data['peso']),
                    'fecha_nacimiento' => $data['fecha_nacimiento_mascota'],
                    'sexo'             => strtoupper(trim($data['sexo'])),
                    'esterilizado'     => $data['esterilizado'] ?: null,
                    'vacunado'         => $data['vacunado'] ?: null,
                    'enfermedades'     => $data['enfermedades'] ?: null,
                    'descripcion'      => trim($data['descripcion']),
                    'ruta_foto'        => null,
                ]);
            }

            fclose($file);
        });

        $this->command->info('Importación CSV con ; completada');
    }
}
