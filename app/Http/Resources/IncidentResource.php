<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
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
            'tracking_number' => $this->tracking_number,
            'report_type' => $this->report_type,
            'description' => $this->description,
            'status' => $this->status,
            'evidence_path' => $this->evidence_path,
            'reporter' => new UserResource($this->whenLoaded('reporter')),
            'student' => new StudentResource($this->whenLoaded('student')),
            'offense' => $this->whenLoaded('offense'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
