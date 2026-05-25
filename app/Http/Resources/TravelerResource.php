<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelerResource extends JsonResource
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
            'family_member_id' => $this->family_member_id,
            'name' => trim($this->first_name.' '.$this->last_name),
            'dob' => $this->dob?->toDateString(),
            'gender' => $this->gender,
            'passport_no' => $this->passport_no,
            'nationality' => $this->nationality,
            'visa_type' => $this->visa_type,
            'emergency_contact' => $this->emergency_contact,
        ];
    }
}
