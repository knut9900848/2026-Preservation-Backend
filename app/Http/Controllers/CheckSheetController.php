<?php

namespace App\Http\Controllers;

use App\Http\Resources\CheckSheetResource;
use App\Http\Resources\CheckSheetItemResource;
use App\Models\Activity;
use App\Models\CheckSheet;
use App\Models\CheckSheetHistory;
use App\Models\User;
use Illuminate\Http\Request;

class CheckSheetController extends Controller
{
    /**
     * Get all check sheets with pagination, search, and filters
     */
    public function index(Request $request)
    {
        $query = CheckSheet::with(['activity', 'equipment', 'reviewer', 'checkSheetItems', 'technicians', 'inspectors']);

        // Filter by equipment
        if ($request->has('equipment_id') && $request->equipment_id) {
            $query->where('equipment_id', $request->equipment_id);
        }

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

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $descending = filter_var($request->get('descending', true), FILTER_VALIDATE_BOOLEAN);
        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        // Pagination
        $perPage = $request->get('per_page', 25);
        $checkSheets = $query->paginate($perPage);

        return response()->json([
            'check_sheets' => CheckSheetResource::collection($checkSheets->items()),
            'total' => $checkSheets->total(),
            'current_page' => $checkSheets->currentPage(),
            'per_page' => $checkSheets->perPage(),
            'last_page' => $checkSheets->lastPage(),
        ]);
    }

    /**
     * Get form options (activities, users) for select boxes
     */
    public function formOptions()
    {
        return response()->json([
            'activities' => Activity::where('is_active', true)
                ->orderBy('code')
                ->get(['id', 'code', 'description']),
            'users' => User::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'email']),
        ]);
    }

    /**
     * Get users filtered by role (technician or inspector)
     */
    public function getUsersByRole(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|string|in:technician,inspector',
        ]);

        $users = User::where('is_active', true)
            ->role($validated['role'])
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return response()->json([
            'role' => $validated['role'],
            'users' => $users,
        ]);
    }

    /**
     * Assign technicians to a check sheet
     */
    public function assignTechnicians(Request $request, CheckSheet $checkSheet)
    {
        $validated = $request->validate([
            'technicians' => 'required|array',
            'technicians.*' => 'exists:users,id',
        ]);

        $checkSheet->technicians()->sync($validated['technicians']);
        $checkSheet->load('technicians');

        return response()->json([
            'message' => 'Technicians assigned successfully',
            'technicians' => $checkSheet->technicians->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            })
        ]);
    }

    /**
     * Revoke technicians from a check sheet
     */
    public function revokeTechnicians(Request $request, CheckSheet $checkSheet)
    {
        $validated = $request->validate([
            'technicians' => 'required|array',
            'technicians.*' => 'exists:users,id',
        ]);

        $checkSheet->technicians()->detach($validated['technicians']);
        $checkSheet->load('technicians');

        return response()->json([
            'message' => 'Technicians revoked successfully',
            'technicians' => $checkSheet->technicians->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            })
        ]);
    }

    /**
     * Assign inspectors to a check sheet
     */
    public function assignInspectors(Request $request, CheckSheet $checkSheet)
    {
        $validated = $request->validate([
            'inspectors' => 'required|array',
            'inspectors.*' => 'exists:users,id',
        ]);

        $checkSheet->inspectors()->sync($validated['inspectors']);
        $checkSheet->load('inspectors');

        return response()->json([
            'message' => 'Inspectors assigned successfully',
            'inspectors' => $checkSheet->inspectors->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            })
        ]);
    }

    /**
     * Revoke inspectors from a check sheet
     */
    public function revokeInspectors(Request $request, CheckSheet $checkSheet)
    {
        $validated = $request->validate([
            'inspectors' => 'required|array',
            'inspectors.*' => 'exists:users,id',
        ]);

        $checkSheet->inspectors()->detach($validated['inspectors']);
        $checkSheet->load('inspectors');

        return response()->json([
            'message' => 'Inspectors revoked successfully',
            'inspectors' => $checkSheet->inspectors->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            })
        ]);
    }

    /**
     * Get check sheet items for a specific check sheet
     */
    public function getCheckSheetItems(CheckSheet $checkSheet)
    {
        $checkSheet->load(['checkSheetItems', 'technicians', 'inspectors', 'equipment', 'activity']);

        return response()->json([
            'equipment_id' => $checkSheet->equipment->id,
            'equipment_name' => $checkSheet->equipment->name,
            'equipment_tag_no' => $checkSheet->equipment->tag_no,
            'check_sheet' => new CheckSheetResource($checkSheet),
            'check_sheet_items' => CheckSheetItemResource::collection($checkSheet->checkSheetItems),
            'technicians' => $checkSheet->technicians->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }),
            'inspectors' => $checkSheet->inspectors->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }),
            'due_date' => $checkSheet->due_date?->format('Y-m-d'),
            'frequency' => $checkSheet->frequency,
        ]);
    }

    /**
     * Update checksheet due date
     */
    public function updateDueDate(Request $request, CheckSheet $checkSheet)
    {
        $validated = $request->validate([
            'due_date' => 'required|date',
        ]);

        $checkSheet->update([
            'due_date' => $validated['due_date'],
        ]);

        $checkSheet->load(['checkSheetItems', 'technicians', 'inspectors', 'activity', 'equipment', 'reviewer']);

        return response()->json([
            'message' => 'Due date updated successfully',
            'check_sheet' => new CheckSheetResource($checkSheet),
        ]);
    }

    /**
     * Save Draft - Update checksheet items without changing status
     */
    public function saveDraftCheckSheet(Request $request, CheckSheet $checkSheet)
    {
        $validated = $request->validate([
            'checksheet_items' => 'required|array',
            'checksheet_items.*.id' => 'required|exists:check_sheet_items,id',
            'checksheet_items.*.status' => 'required|integer|min:0|max:3',
            'checksheet_items.*.remarks' => 'nullable|string',
        ]);

        foreach ($validated['checksheet_items'] as $item) {
            \App\Models\CheckSheetItem::where('id', $item['id'])
                ->where('check_sheet_id', $checkSheet->id)
                ->update([
                    'status' => $item['status'],
                    'remarks' => $item['remarks'] ?? null,
                ]);
        }

        return response()->json([
            'message' => 'Draft saved successfully',
        ]);
    }

    /**
     * Complete checksheet - Step 1: Draft -> Completed
     */
    public function completeChecksheet(Request $request, CheckSheet $checkSheet)
    {
        if ($checkSheet->status !== 'Draft') {
            return response()->json([
                'message' => 'Checksheet must be in draft status to complete'
            ], 400);
        }

        $validated = $request->validate([
            'checksheet_items' => 'required|array',
            'checksheet_items.*.id' => 'required|exists:check_sheet_items,id',
            'checksheet_items.*.status' => 'required|integer|min:0|max:3',
        ]);

        // Check if any item has status 1 (incomplete/pending)
        $hasIncompleteItems = collect($validated['checksheet_items'])
            ->contains(fn($item) => $item['status'] === 1);

        if ($hasIncompleteItems) {
            return response()->json([
                'message' => 'Cannot complete checksheet with incomplete items (status 1)'
            ], 400);
        }

        $previousStatus = $checkSheet->status;

        $checkSheet->update([
            'status' => 'Completed',
        ]);

        // Update all checksheet items status
        foreach ($validated['checksheet_items'] as $item) {
            \App\Models\CheckSheetItem::where('id', $item['id'])
                ->where('check_sheet_id', $checkSheet->id)
                ->update(['status' => $item['status']]);
        }

        // Record history
        CheckSheetHistory::create([
            'check_sheet_id' => $checkSheet->id,
            'user_id' => $request->user()->id,
            'action' => 'completed',
            'from_status' => $previousStatus,
            'to_status' => 'Completed',
            'metadata' => [
                'items_updated' => count($validated['checksheet_items']),
            ],
        ]);

        $checkSheet->load(['checkSheetItems', 'technicians', 'inspectors', 'activity', 'equipment', 'reviewer']);

        return response()->json([
            'message' => 'Checksheet completed successfully',
            'check_sheet' => new CheckSheetResource($checkSheet),
        ]);
    }

    /**
     * Review checksheet - Step 2: Completed -> Reviewed
     */
    public function reviewChecksheet(Request $request, CheckSheet $checkSheet)
    {
        if ($checkSheet->status !== 'Completed') {
            return response()->json([
                'message' => 'Checksheet must be in completed status to review'
            ], 400);
        }

        $previousStatus = $checkSheet->status;

        $checkSheet->update([
            'status' => 'Reviewed',
        ]);

        // Record history
        CheckSheetHistory::create([
            'check_sheet_id' => $checkSheet->id,
            'user_id' => $request->user()->id,
            'action' => 'reviewed',
            'from_status' => $previousStatus,
            'to_status' => 'Reviewed',
        ]);

        $checkSheet->load(['checkSheetItems', 'technicians', 'inspectors', 'activity', 'equipment']);

        return response()->json([
            'message' => 'Checksheet reviewed successfully',
            'check_sheet' => new CheckSheetResource($checkSheet),
        ]);
    }

    /**
     * Approve checksheet - Step 3: Reviewed -> Approved
     */
    public function approveChecksheet(Request $request, CheckSheet $checkSheet)
    {
        if ($checkSheet->status !== 'Reviewed') {
            return response()->json([
                'message' => 'Checksheet must be in reviewed status to approve'
            ], 400);
        }

        $previousStatus = $checkSheet->status;

        $checkSheet->update([
            'status' => 'Approved',
        ]);

        // Record history
        CheckSheetHistory::create([
            'check_sheet_id' => $checkSheet->id,
            'user_id' => $request->user()->id,
            'action' => 'approved',
            'from_status' => $previousStatus,
            'to_status' => 'Approved',
        ]);

        $checkSheet->load(['checkSheetItems', 'technicians', 'inspectors', 'activity', 'equipment', 'reviewer']);

        return response()->json([
            'message' => 'Checksheet approved successfully',
            'check_sheet' => new CheckSheetResource($checkSheet),
        ]);
    }

    /**
     * Reject checksheet - Return to Draft status
     */
    public function rejectChecksheet(Request $request, CheckSheet $checkSheet)
    {
        if ($checkSheet->status === 'Draft') {
            return response()->json([
                'message' => 'Checksheet is already in draft status'
            ], 400);
        }

        if ($checkSheet->status === 'Approved') {
            return response()->json([
                'message' => 'Cannot reject an approved checksheet'
            ], 400);
        }

        $previousStatus = $checkSheet->status;

        $checkSheet->update([
            'status' => 'Draft',
        ]);

        // Record history
        CheckSheetHistory::create([
            'check_sheet_id' => $checkSheet->id,
            'user_id' => $request->user()->id,
            'action' => 'rejected',
            'from_status' => $previousStatus,
            'to_status' => 'Draft',
        ]);

        $checkSheet->load(['checkSheetItems', 'technicians', 'inspectors', 'activity', 'equipment']);

        return response()->json([
            'message' => 'Checksheet rejected and returned to draft',
            'check_sheet' => new CheckSheetResource($checkSheet),
        ]);
    }

    /**
     * Get checksheet history
     */
    public function getCheckSheetHistory(CheckSheet $checkSheet)
    {
        $histories = CheckSheetHistory::where('check_sheet_id', $checkSheet->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'histories' => $histories->map(function ($history) {
                return [
                    'id' => $history->id,
                    'action' => $history->action,
                    'from_status' => $history->from_status,
                    'to_status' => $history->to_status,
                    'user' => [
                        'id' => $history->user->id,
                        'name' => $history->user->name,
                        'email' => $history->user->email,
                    ],
                    'metadata' => $history->metadata,
                    'created_at' => $history->created_at,
                ];
            }),
        ]);
    }

    /**
     * Generate next round checksheet
     */
    public function generateNextRoundChecksheet(Request $request, CheckSheet $checkSheet)
    {
        if ($checkSheet->status !== 'Approved') {
            return response()->json([
                'message' => 'Checksheet must be approved before generating next round'
            ], 400);
        }

        // Verify that this is the latest round
        $latestRound = CheckSheet::where('equipment_id', $checkSheet->equipment_id)
            ->where('activity_id', $checkSheet->activity_id)
            ->max('current_round');

        if ($checkSheet->current_round !== $latestRound) {
            return response()->json([
                'message' => 'Only the latest round checksheet can generate next round',
                'current_round' => $checkSheet->current_round,
                'latest_round' => $latestRound,
            ], 400);
        }

        // Load relationships
        $checkSheet->load(['activity', 'technicians', 'inspectors', 'checkSheetItems', 'equipment']);

        // Verify activity is still assigned
        $activityExists = $checkSheet->equipment->assignedActivities()
            ->where('activity_id', $checkSheet->activity_id)
            ->exists();

        if (!$activityExists) {
            return response()->json([
                'message' => 'Activity is no longer assigned to this equipment'
            ], 400);
        }

        $nextRound = $checkSheet->current_round + 1;

        // Calculate next due date
        $nextDueDate = null;
        if ($checkSheet->due_date && $checkSheet->frequency) {
            $nextDueDate = \Carbon\Carbon::parse($checkSheet->due_date)
                ->addDays($checkSheet->frequency)
                ->format('Y-m-d');
        }

        // Create new CheckSheet
        $newCheckSheet = CheckSheet::create([
            'activity_id' => $checkSheet->activity_id,
            'equipment_id' => $checkSheet->equipment_id,
            'current_round' => $nextRound,
            'status' => 'Draft',
            'due_date' => $nextDueDate,
            'frequency' => $checkSheet->frequency,
            'activity_code' => $checkSheet->activity_code,
        ]);

        // Copy items
        foreach ($checkSheet->checkSheetItems as $item) {
            \App\Models\CheckSheetItem::create([
                'equipment_id' => $checkSheet->equipment_id,
                'check_sheet_id' => $newCheckSheet->id,
                'activity' => $item->activity,
                'description' => $item->description,
                'status' => 'Draft',
                'order' => $item->order,
            ]);
        }

        // Copy technicians
        if ($checkSheet->technicians->isNotEmpty()) {
            $newCheckSheet->technicians()->sync($checkSheet->technicians->pluck('id'));
        }

        // Copy inspectors
        if ($checkSheet->inspectors->isNotEmpty()) {
            $newCheckSheet->inspectors()->sync($checkSheet->inspectors->pluck('id'));
        }

        $newCheckSheet->load(['checkSheetItems', 'technicians', 'inspectors', 'activity', 'equipment']);

        return response()->json([
            'message' => 'Next round checksheet generated successfully',
            'check_sheet' => new CheckSheetResource($newCheckSheet),
            'previous_round' => $checkSheet->current_round,
            'new_round' => $nextRound,
        ]);
    }
}
