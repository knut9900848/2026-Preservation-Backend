<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
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
            'name' => $this->name,
            'tag_no' => $this->tag_no,
            'category_id' => $this->category_id,
            'category_name' => $this->category?->name,
            'sub_category_id' => $this->sub_category_id,
            'sub_category_name' => $this->subCategory?->name,
            'supplier_id' => $this->supplier_id,
            'supplier_name' => $this->supplier?->name,
            'current_location_id' => $this->current_location_id,
            'current_location_name' => $this->currentLocation?->name,
            'serial_number' => $this->serial_number,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
