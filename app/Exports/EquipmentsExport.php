<?php

namespace App\Exports;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EquipmentsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithEvents, WithColumnWidths
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Equipment::with(['category', 'subCategory', 'supplier', 'currentLocation']);

        if ($this->request->filled('category_id')) {
            $query->where('category_id', $this->request->category_id);
        }

        if ($this->request->filled('sub_category_id')) {
            $query->where('sub_category_id', $this->request->sub_category_id);
        }

        if ($this->request->filled('supplier_id')) {
            $query->where('supplier_id', $this->request->supplier_id);
        }

        if ($this->request->filled('current_location_id')) {
            $query->where('current_location_id', $this->request->current_location_id);
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('tag_no', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortBy = $this->request->get('sort_by', 'created_at');
        $descending = filter_var($this->request->get('descending', true), FILTER_VALIDATE_BOOLEAN);
        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        return $query;
    }

    public function headings(): array
    {
        return [
            'Tag No',
            'Name',
            'Category',
            'Sub Category',
            'Supplier',
            'Current Location',
            'Serial Number',
            'Description',
            'Active',
        ];
    }

    public function map($equipment): array
    {
        return [
            $equipment->tag_no,
            $equipment->name,
            $equipment->category?->name,
            $equipment->subCategory?->name,
            $equipment->supplier?->name,
            $equipment->currentLocation?->name,
            $equipment->serial_number,
            $equipment->description,
            $equipment->is_active ? 'Yes' : 'No',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18, // Tag No
            'B' => 28, // Name
            'C' => 18, // Category
            'D' => 18, // Sub Category
            'E' => 20, // Supplier
            'F' => 20, // Current Location
            'G' => 18, // Serial Number
            'H' => 35, // Description
            'I' => 10, // Active
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2B5797'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastCol = 'I';

                // Header row height
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Freeze header row
                $sheet->freezePane('A2');

                // Auto-filter
                $sheet->setAutoFilter("A1:{$lastCol}1");

                // Data rows styling
                if ($lastRow > 1) {
                    // All data cells: border + vertical center
                    $sheet->getStyle("A2:{$lastCol}{$lastRow}")->applyFromArray([
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'D9D9D9'],
                            ],
                        ],
                    ]);

                    // Center-align: Active column
                    $sheet->getStyle("I2:I{$lastRow}")->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Zebra striping
                    for ($row = 2; $row <= $lastRow; $row++) {
                        if ($row % 2 === 0) {
                            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'F2F6FC'],
                                ],
                            ]);
                        }
                    }
                }

                // Header border (on top of fill)
                $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '1B3A6B'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
