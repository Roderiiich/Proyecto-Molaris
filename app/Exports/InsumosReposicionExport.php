<?php

namespace App\Exports;

use App\Models\Articulo; // Ajusta el modelo según corresponda
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class InsumosReposicionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * Retorna la colección de insumos con stock crítico o agotados.
     */
    public function collection(): Collection
    {
        return Articulo::whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orWhere('stock_actual', '<=', 0)
            ->get();
    }

    public function headings(): array
    {
        return [
            
            'Nombre del Insumo',
            'Categoría',
            'Unidad de Medida',
            'Stock Actual',
            'Stock Mínimo',
            'Estado',
            'Sugerido a Pedir',
        ];
    }

    public function map($item): array
    {
        $estado = $item->stock_actual <= 0 ? 'SIN STOCK (Agotado)' : 'STOCK CRÍTICO';
        
        // Cantidad sugerida para reponer
        $sugerido = max(($item->stock_minimo * 2) - $item->stock_actual, $item->stock_minimo);

        return [
            
            $item->nombre,
            $item->categoria,
            $item->unidad_medida,
            $item->stock_actual,
            $item->stock_minimo,
            $estado,
            $sugerido,
        ];
    }

    /**
     * Define los estilos para la hoja.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            // Estilo para los encabezados de la tabla
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0F172A']
                ],
            ],
        ];
    }
}