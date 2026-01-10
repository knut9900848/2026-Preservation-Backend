<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
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
            'assigned_equipments' => $this->whenLoaded('assignedEquipments', function () {
                return $this->assignedEquipments->map(function ($equipment) {
                    return [
                        'id' => $equipment->id,
                        'name' => $equipment->name,
                        'tag_no' => $equipment->tag_no,
                    ];
                });
            }),
            'code' => $this->code,
            'description' => $this->description,
            'notes' => $this->notes,
            'frequency' => $this->frequency,
            'is_active' => $this->is_active,
            'activity_items' => ActivityItemResource::collection($this->whenLoaded('activityItems')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
