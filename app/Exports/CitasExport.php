<?php

namespace App\Exports;

use App\Models\Cita;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CitasExport implements FromCollection, WithHeadings, WithMapping
{
    protected $fecha_inicio;
    protected $fecha_fin;
    protected $estado;
    protected $usuario_id;
    protected $cliente;

    public function __construct($fecha_inicio, $fecha_fin, $estado, $usuario_id, $cliente)
    {
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->estado = $estado;
        $this->usuario_id = $usuario_id;
        $this->cliente = $cliente;
    }

    public function collection()
    {
        $query = Cita::with(['cliente', 'mascota', 'usuario'])
            ->orderBy('fecha', 'desc');

        // 🔹 Filtro por rango de fechas
        if ($this->fecha_inicio && $this->fecha_fin) {
            $query->whereBetween('fecha', [$this->fecha_inicio, $this->fecha_fin]);
        }

        // 🔹 Filtro por estado
        if ($this->estado) {
            $query->where('estado', $this->estado);
        }

        // 🔹 Filtro por usuario/profesional
        if ($this->usuario_id) {
            $query->where('usuario_id', $this->usuario_id);
        }

        // 🔹 Filtro por cliente (nombre o documento)
        if ($this->cliente) {
            $query->whereHas('cliente', function ($q) {
                $q->where('name', 'like', "%{$this->cliente}%")
                    ->orWhere('documento', 'like', "%{$this->cliente}%");
            });
        }

        return $query->get();
    }

    // Encabezados del Excel
    public function headings(): array
    {
        return [
            'ID',
            'Cliente',
            'Documento',
            'Mascota',
            'Groomer',
            'Fecha de la cita',
            'Hora Inicio',
            'Hora Final',
            'Estado',
            'Observaciones',
        ];
    }

    // Mapeo de los datos a columnas
    public function map($cita): array
    {
        return [
            $cita->id,
            optional($cita->cliente)->name,
            optional($cita->cliente)->documento,
            optional($cita->mascota)->name,
            optional($cita->usuario)->name,
            $cita->fecha,
            $cita->hora_inicio,
            $cita->hora_final,
            ucfirst($cita->estado),
            $cita->descripcion,
        ];
    }
}
