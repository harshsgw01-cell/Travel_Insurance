<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClaimResource extends JsonResource
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
            'claim_number' => $this->claim_number,
            'policy_id' => $this->policy_id,
            'traveler_id' => $this->traveler_id,
            'claim_type' => $this->claim_type,
            'incident_date' => $this->incident_date?->toDateString(),
            'amount_claimed' => $this->amount_claimed,
            'amount_approved' => $this->amount_approved,
            'status' => $this->status,
            'remarks' => $this->remarks,
        ];
    }
}
