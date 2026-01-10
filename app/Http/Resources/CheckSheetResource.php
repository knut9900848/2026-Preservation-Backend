<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckSheetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'activity_id' => $this->activity_id,
            'activity_code' => $this->activity_code,
            'activity_description' => $this->activity?->description,
            'equipment_id' => $this->equipment_id,
            'equipment_name' => $this->equipment?->name,
            'equipment_tag_no' => $this->equipment?->tag_no,
            'current_round' => $this->current_round,
            'sheet_number' => $this->sheet_number,
            'reviewed_by' => $this->reviewed_by,
            'reviewed_by_name' => $this->reviewer?->name,
            'reviewed_date' => $this->reviewed_date?->format('Y-m-d'),
            'performed_date' => $this->performed_date?->format('Y-m-d'),
            'due_date' => $this->due_date?->format('Y-m-d'),
            'notes' => $this->notes,
            'frequency' => $this->frequency,
            'status' => $this->status,
            'technicians' => $this->whenLoaded('technicians', function () {
                return $this->technicians->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ];
                });
            }),
            'inspectors' => $this->whenLoaded('inspectors', function () {
                return $this->inspectors->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ];
                });
            }),
            'check_sheet_items' => CheckSheetItemResource::collection($this->whenLoaded('checkSheetItems')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
