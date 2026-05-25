<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'customer_code' => $this->customer_code,
            'name' => trim($this->first_name.' '.$this->last_name),
            'email' => $this->email,
            'mobile' => $this->mobile,
            'dob' => $this->dob?->toDateString(),
            'gender' => $this->gender,
            'passport_no' => $this->passport_no,
            'nationality' => $this->nationality,
            'status' => $this->status,
            'kyc_status' => $this->kyc_status,
            'family_members' => FamilyMemberResource::collection($this->whenLoaded('familyMembers')),
            'policies' => PolicyResource::collection($this->whenLoaded('policies')),
        ];
    }
}
