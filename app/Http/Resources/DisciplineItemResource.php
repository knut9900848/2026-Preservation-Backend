<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DisciplineItemResource extends JsonResource
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
            'discipline_id' => $this->discipline_id,
            'discipline' => $this->whenLoaded('discipline', function () {
                return [
                    'id' => $this->discipline->id,
                    'name' => $this->discipline->name,
                    'code' => $this->discipline->code,
                ];
            }),
            'code' => $this->code,
            'name' => $this->name,
            'method' => $this->method,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
