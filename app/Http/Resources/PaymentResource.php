<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'policy_id' => $this->policy_id,
            'transaction_id' => $this->transaction_id,
            'gateway' => $this->gateway,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'paid_at' => $this->paid_at?->toIso8601String(),
        ];
    }
}
