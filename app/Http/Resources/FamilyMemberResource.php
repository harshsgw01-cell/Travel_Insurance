<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FamilyMemberResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'relationship' => $this->relationship,
            'name' => trim($this->first_name.' '.$this->last_name),
            'dob' => $this->dob?->toDateString(),
            'passport_no' => $this->passport_no,
            'gender' => $this->gender,
            'dependent' => $this->dependent,
        ];
    }
}
