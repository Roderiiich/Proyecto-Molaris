<?php

namespace App\Exports;

use App\Models\Paciente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Enumerable; // <-- Importante incluir esto

class PacientesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Enumerable // <-- Agregamos el tipo de retorno ': Enumerable'
    {
        return Paciente::all();
    }

    /**
     * Encabezados de las columnas en el archivo Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'RUT',
            'Nombre Completo',
            'Teléfono',
            'Correo Electrónico',
            'Fecha de Registro',
        ];
    }

    /**
     * Mapeo de los campos de cada paciente
     */
    public function map($paciente): array
    {
        return [
            $paciente->id,
            $paciente->rut,
            $paciente->nombre,
            $paciente->telefono,
            $paciente->correo,
            $paciente->created_at ? $paciente->created_at->format('d/m/Y H:i') : '',
        ];
    }
}