<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MovementsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected $movements;
    public function __construct($movements)
    {
        $this->movements = $movements;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->movements->map(function($movement){
            return [
                $movement->id,
                $movement->date,
                $movement->type == 1 ? 'Ingreso' : 'Salida',
                $movement->serie,
                $movement->correlative,
                $movement->warehouse->name,
                $movement->reason->name,
                $movement->total,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'id',
            'Fecha',
            'Tipo',
            'Serie',
            'Correlativo',
            'Almacén',
            'Motivo',
            'Total',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        $fullRange = 'A1:' . $lastColumn . $lastRow;
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFCCCCCC',
                        ],
                ],
                'alignment' => [
                    'horizontal' => 'center',
                ],
            ],
            $fullRange => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]
        ];
    }
    public function registerEvents(): array
    {
        return [
          AfterSheet::class => function(AfterSheet $event) {
            $event->sheet->getDelegate()->setSelectedCell('A1');
          }
        ];
    }
}
