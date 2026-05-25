<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Policy;
use App\Models\Traveler;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PolicyService
{
    public function create(array $data): Policy
    {
        return DB::transaction(function () use ($data) {
            $plan = Plan::findOrFail($data['plan_id']);
            $taxAmount = (float) ($data['tax_amount'] ?? round($data['premium_amount'] * 0.18, 2));

            $policy = Policy::create([
                ...Arr::except($data, ['traveler_ids', 'tax_amount']),
                'policy_number' => $this->nextPolicyNumber(),
                'tax_amount' => $taxAmount,
                'total_amount' => round($data['premium_amount'] + $taxAmount, 2),
                'status' => 'pending_payment',
                'payment_status' => 'pending',
            ]);

            $travelers = Traveler::whereIn('id', $data['traveler_ids'])->get();
            $memberPremium = round($data['premium_amount'] / max($travelers->count(), 1), 2);

            foreach ($travelers as $traveler) {
                $policy->members()->create([
                    'traveler_id' => $traveler->id,
                    'relationship' => $traveler->familyMember?->relationship ?? 'Self',
                    'coverage_amount' => $plan->coverage_amount,
                    'premium' => $memberPremium,
                ]);
            }

            return $policy->load(['customer', 'plan', 'members.traveler']);
        });
    }

    private function nextPolicyNumber(): string
    {
        return 'TI-'.now()->format('Ymd').'-'.Str::upper(Str::random(8));
    }
}
