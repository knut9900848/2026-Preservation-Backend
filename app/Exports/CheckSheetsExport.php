<?php

namespace App\Exports;

use App\Models\CheckSheet;
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

class CheckSheetsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithEvents, WithColumnWidths
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = CheckSheet::with([
            'activity',
            'equipment.category',
            'equipment.subCategory',
            'equipment.currentLocation',
            'equipment.supplier',
            'reviewer',
            'technicians',
            'inspectors',
        ]);

        // Technician role: only show checksheets assigned to them
        $user = $this->request->user();
        if ($user && $user->hasRole('technician')) {
            $query->whereHas('technicians', fn($q) => $q->where('users.id', $user->id));
        }

        if ($this->request->filled('equipment_id')) {
            $query->where('equipment_id', $this->request->equipment_id);
        }

        if ($this->request->filled('activity_id')) {
            $query->where('activity_id', $this->request->activity_id);
        }

        if ($this->request->has('status') && $this->request->status !== null) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('current_round')) {
            $query->where('current_round', $this->request->current_round);
        }

        if ($this->request->filled('category_id')) {
            $query->whereHas('equipment', fn($q) => $q->where('category_id', $this->request->category_id));
        }

        if ($this->request->filled('sub_category_id')) {
            $query->whereHas('equipment', fn($q) => $q->where('sub_category_id', $this->request->sub_category_id));
        }

        if ($this->request->filled('current_location_id')) {
            $query->whereHas('equipment', fn($q) => $q->where('current_location_id', $this->request->current_location_id));
        }

        if ($this->request->filled('supplier_id')) {
            $query->whereHas('equipment', fn($q) => $q->where('supplier_id', $this->request->supplier_id));
        }

        if ($this->request->filled('technician_id')) {
            $query->whereHas('technicians', fn($q) => $q->where('users.id', $this->request->technician_id));
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sheet_number', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('equipment', function ($eq) use ($search) {
                        $eq->where('name', 'like', "%{$search}%")
                            ->orWhere('tag_no', 'like', "%{$search}%");
                    })
                    ->orWhereHas('activity', function ($act) use ($search) {
                        $act->where('code', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    });
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
            'Sheet Number',
            'Equipment Tag No',
            'Equipment Name',
            'Category',
            'Sub Category',
            'Location',
            'Supplier',
            'Activity Code',
            'Activity Description',
            'Round',
            'Frequency',
            'Status',
            'Due Date',
            'Performed Date',
            'Reviewed Date',
            'Reviewed By',
            'Technicians',
            'Inspectors',
            'Notes',
        ];
    }

    public function map($checkSheet): array
    {
        return [
            $checkSheet->sheet_number,
            $checkSheet->equipment?->tag_no,
            $checkSheet->equipment?->name,
            $checkSheet->equipment?->category?->name,
            $checkSheet->equipment?->subCategory?->name,
            $checkSheet->equipment?->currentLocation?->name,
            $checkSheet->equipment?->supplier?->name,
            $checkSheet->activity_code,
            $checkSheet->activity?->description,
            $checkSheet->current_round,
            $checkSheet->frequency,
            $checkSheet->status,
            $checkSheet->due_date?->format('Y-m-d'),
            $checkSheet->performed_date?->format('Y-m-d'),
            $checkSheet->reviewed_date?->format('Y-m-d'),
            $checkSheet->reviewer?->name,
            $checkSheet->technicians->pluck('name')->implode(', '),
            $checkSheet->inspectors->pluck('name')->implode(', '),
            $checkSheet->notes,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22, // Sheet Number
            'B' => 18, // Tag No
            'C' => 22, // Equipment Name
            'D' => 16, // Category
            'E' => 16, // Sub Category
            'F' => 16, // Location
            'G' => 16, // Supplier
            'H' => 14, // Activity Code
            'I' => 24, // Activity Description
            'J' => 8,  // Round
            'K' => 10, // Frequency
            'L' => 12, // Status
            'M' => 12, // Due Date
            'N' => 14, // Performed Date
            'O' => 14, // Reviewed Date
            'P' => 16, // Reviewed By
            'Q' => 20, // Technicians
            'R' => 20, // Inspectors
            'S' => 30, // Notes
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
                $lastCol = 'S';

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

                    // Center-align: Round, Frequency, Status, Dates
                    foreach (['J', 'K', 'L', 'M', 'N', 'O'] as $col) {
                        $sheet->getStyle("{$col}2:{$col}{$lastRow}")->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }

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
