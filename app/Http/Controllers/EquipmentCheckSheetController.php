<?php

namespace App\Http\Controllers;

use App\Http\Resources\CheckSheetResource;
use App\Http\Resources\CheckSheetItemResource;
use App\Models\Activity;
use App\Models\CheckSheet;
use App\Models\CheckSheetHistory;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Http\Request;

class EquipmentCheckSheetController extends Controller
{
    /**
     * Get all check sheets for a specific equipment with pagination, search, and filter
     */
    public function getCheckSheets(Request $request, Equipment $equipment)
    {
        $query = CheckSheet::with(['activity', 'equipment', 'reviewer', 'checkSheetItems', 'technicians', 'inspectors'])
            ->where('equipment_id', $equipment->id);

        // Filter by activity
        if ($request->has('activity_id') && $request->activity_id) {
            $query->where('activity_id', $request->activity_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        // Filter by current_round
        if ($request->has('current_round') && $request->current_round) {
            $query->where('current_round', $request->current_round);
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sheet_number', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->query('sort_by', 'created_at');
        $descending = filter_var($request->query('descending', true), FILTER_VALIDATE_BOOLEAN);
        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        // Pagination
        $perPage = 25;
        $checkSheets = $query->paginate($perPage);

        return response()->json([
            'check_sheets' => CheckSheetResource::collection($checkSheets->items()),
            'total' => $checkSheets->total(),
            'current_page' => $checkSheets->currentPage(),
            'per_page' => $checkSheets->perPage(),
            'last_page' => $checkSheets->lastPage(),
        ]);
    }
}
