<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyResource extends JsonResource
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
            'policy_number' => $this->policy_number,
            'customer_id' => $this->customer_id,
            'plan_id' => $this->plan_id,
            'policy_type' => $this->policy_type,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'destination_country' => $this->destination_country,
            'trip_type' => $this->trip_type,
            'premium_amount' => $this->premium_amount,
            'tax_amount' => $this->tax_amount,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'issued_at' => $this->issued_at?->toIso8601String(),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'plan' => new PlanResource($this->whenLoaded('plan')),
            'members' => $this->whenLoaded('members'),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'claims' => ClaimResource::collection($this->whenLoaded('claims')),
        ];
    }
}
