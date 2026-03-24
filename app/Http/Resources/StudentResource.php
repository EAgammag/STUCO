<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'student_id' => $this->student_id,
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'program' => $this->program,
            'college' => $this->college,
            'year_level' => $this->year_level,
            'section' => $this->section,
            'violation_count' => $this->whenCounted('violationRecords'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
