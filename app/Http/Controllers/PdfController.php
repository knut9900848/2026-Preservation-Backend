<?php

namespace App\Http\Controllers;

use Spatie\LaravelPdf\Facades\Pdf;

class PdfController extends Controller
{
    /**
     * Generate a test PDF and return as download
     */
    public function testDownload()
    {
        $data = [
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'items' => [
                ['name' => 'Visual Inspection', 'status' => 'Completed', 'remarks' => 'No issues found'],
                ['name' => 'Pressure Test', 'status' => 'Completed', 'remarks' => 'Within range'],
                ['name' => 'Lubrication Check', 'status' => 'Pending', 'remarks' => 'Scheduled for next week'],
                ['name' => 'Valve Operation', 'status' => 'Completed', 'remarks' => 'Operating normally'],
                ['name' => 'Corrosion Check', 'status' => 'N/A', 'remarks' => 'Not applicable this round'],
            ],
        ];

        $pdf = Pdf::view('pdf.test', $data)
            ->format('a4');

        if ($chromePath = config('laravel-pdf.browsershot.chrome_path')) {
            $pdf->withBrowsershot(function ($browsershot) use ($chromePath) {
                $browsershot->setChromePath($chromePath)
                    ->noSandbox();
            });
        }

        return $pdf->name('test-report.pdf');
    }
}
