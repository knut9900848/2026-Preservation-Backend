<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckSheetItemResource extends JsonResource
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
            'equipment_id' => $this->equipment_id,
            'equipment_name' => $this->equipment?->name,
            'check_sheet_id' => $this->check_sheet_id,
            'activity' => $this->activity,
            'description' => $this->description,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'remarks' => $this->remarks,
            'order' => $this->order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Get status label
     */
    private function getStatusLabel()
    {
        return match($this->status) {
            0 => 'Rejected',
            1 => 'Completed',
            2 => 'Holding',
            3 => 'N/A',
            default => 'Unknown'
        };
    }
}
