<?php

namespace App\Http\Controllers;

use App\Models\CheckSheet;
use App\Models\CheckSheetHistory;
use App\Models\CheckSheetReport;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;

class CheckSheetReportController extends Controller
{
    /**
     * Get PDF data for a checksheet
     */
    private function getPdfData(CheckSheet $checkSheet, ?string $revisionNumber = null): array
    {
        $checkSheet->load([
            'activity',
            'equipment.category',
            'equipment.subCategory',
            'equipment.supplier',
            'equipment.currentLocation',
            'checkSheetItems' => fn($q) => $q->orderBy('order'),
            'technicians',
            'inspectors',
            'reviewer',
            'photoGroups.photos',
        ]);

        $completedDate = CheckSheetHistory::where('check_sheet_id', $checkSheet->id)
            ->where('action', 'completed')
            ->latest()
            ->value('created_at');

        return [
            'checkSheet' => $checkSheet,
            'equipment' => $checkSheet->equipment,
            'checkSheetItems' => $checkSheet->checkSheetItems,
            'technicians' => $checkSheet->technicians,
            'inspectors' => $checkSheet->inspectors,
            'photoGroups' => $checkSheet->photoGroups,
            'setting' => Setting::instance(),
            'revisionNumber' => $revisionNumber,
            'completedDate' => $completedDate,
        ];
    }

    /**
     * Preview preservation report PDF (on-the-fly, not saved)
     */
    public function preview(CheckSheet $checkSheet)
    {
        $data = $this->getPdfData($checkSheet);

        return Pdf::view('pdf.preservation-report', $data)
            ->format('a4')
            ->name('preservation-report-' . $checkSheet->sheet_number . '.pdf');
    }

    /**
     * List all reports for a checksheet
     */
    public function index(CheckSheet $checkSheet)
    {
        $reports = $checkSheet->reports()
            ->with('generatedBy')
            ->get();

        return response()->json([
            'reports' => $reports->map(fn($report) => [
                'id' => $report->id,
                'revision_number' => $report->revision_number,
                'file_name' => $report->file_name,
                'file_size' => $report->file_size,
                'checksheet_status' => $report->checksheet_status,
                'notes' => $report->notes,
                'generated_by' => [
                    'id' => $report->generatedBy->id,
                    'name' => $report->generatedBy->name,
                ],
                'created_at' => $report->created_at,
            ]),
        ]);
    }

    /**
     * Generate a new report (create the PDF and store it)
     */
    public function store(Request $request, CheckSheet $checkSheet)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        // Determine next revision number (first=0, second=1.0, third=2.0...)
        $latestRevision = $checkSheet->reports()->max('revision_number');
        $nextRevision = is_null($latestRevision) ? 0 : $latestRevision + 1.0;

        // Load relationships and build PDF data
        $formattedRevision = $nextRevision > 0 ? number_format($nextRevision, 1) : null;
        $data = $this->getPdfData($checkSheet, $formattedRevision);

        // Build file paths (first version has no revision suffix)
        $baseName = 'preservation-report-' . $checkSheet->sheet_number;
        $fileName = $nextRevision > 0
            ? $baseName . '-rev' . number_format($nextRevision, 1) . '.pdf'
            : $baseName . '.pdf';
        $storagePath = 'checksheet-reports/' . $checkSheet->id . '/' . $fileName;

        // Generate and save PDF to local disk
        Pdf::view('pdf.preservation-report', $data)
            ->format('a4')
            ->disk('local')
            ->save($storagePath);

        // Get file size
        $fileSize = Storage::disk('local')->size($storagePath);

        // Create report record
        $report = CheckSheetReport::create([
            'check_sheet_id' => $checkSheet->id,
            'generated_by' => $request->user()->id,
            'revision_number' => $nextRevision,
            'file_path' => $storagePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'checksheet_status' => $checkSheet->status,
            'notes' => $validated['notes'] ?? null,
        ]);

        $report->load('generatedBy');

        return response()->json([
            'message' => 'Report generated successfully',
            'report' => [
                'id' => $report->id,
                'revision_number' => $report->revision_number,
                'file_name' => $report->file_name,
                'file_size' => $report->file_size,
                'checksheet_status' => $report->checksheet_status,
                'notes' => $report->notes,
                'generated_by' => [
                    'id' => $report->generatedBy->id,
                    'name' => $report->generatedBy->name,
                ],
                'created_at' => $report->created_at,
            ],
        ], 201);
    }

    /**
     * Download a specific report's PDF
     */
    public function download(CheckSheet $checkSheet, CheckSheetReport $report)
    {
        if ($report->check_sheet_id !== $checkSheet->id) {
            return response()->json(['message' => 'Report not found for this checksheet'], 404);
        }

        if (!$report->fileExists()) {
            return response()->json(['message' => 'PDF file not found on disk'], 404);
        }

        $fullPath = Storage::disk('local')->path($report->file_path);

        return response()->download($fullPath, $report->file_name, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
