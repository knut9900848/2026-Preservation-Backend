<?php

namespace App\Http\Controllers;

use App\Models\CheckSheet;
use App\Models\CheckSheetHistory;
use App\Models\Equipment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $statusCounts = CheckSheet::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Weekly accepted checksheets for the last 8 weeks
        $weeklyAccepted = [];
        for ($i = 7; $i >= 0; $i--) {
            $weekStart = Carbon::now()->startOfWeek()->subWeeks($i);
            $weekEnd = $weekStart->copy()->endOfWeek();

            $weeklyAccepted[] = [
                'week_start' => $weekStart->format('Y-m-d'),
                'week_end' => $weekEnd->format('Y-m-d'),
                'label' => $weekStart->format('M d'),
                'count' => CheckSheetHistory::where('action', 'accepted')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->count(),
            ];
        }

        return response()->json([
            'equipment_count' => Equipment::count(),
            'checksheet_total' => CheckSheet::count(),
            'checksheet_overdue' => CheckSheet::whereNotNull('due_date')
                ->where('due_date', '<', Carbon::today())
                ->whereNotIn('status', ['Accepted'])
                ->count(),
            'checksheet_status' => [
                'Draft' => $statusCounts->get('Draft', 0),
                'Completed' => $statusCounts->get('Completed', 0),
                'Approved' => $statusCounts->get('Approved', 0),
                'Accepted' => $statusCounts->get('Accepted', 0),
            ],
            'weekly_accepted' => $weeklyAccepted,
        ]);
    }
}
