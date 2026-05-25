<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'code' => $this->code,
            'policy_type' => $this->policy_type,
            'base_premium' => $this->base_premium,
            'coverage_amount' => $this->coverage_amount,
            'age_limits' => [
                'min' => $this->min_age,
                'max' => $this->max_age,
            ],
            'max_family_members' => $this->max_family_members,
            'covered_countries' => $this->covered_countries,
            'benefits' => $this->benefits,
            'add_ons' => $this->add_ons,
            'is_active' => $this->is_active,
        ];
    }
}
