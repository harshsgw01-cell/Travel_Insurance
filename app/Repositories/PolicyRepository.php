<?php

namespace App\Repositories;

use App\Models\Policy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PolicyRepository
{
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = Policy::with(['customer', 'plan', 'payments', 'claims'])
            ->latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        return $query->paginate($perPage);
    }

    public function findWithRelations(Policy $policy): Policy
    {
        return $policy->load([
            'customer',
            'plan',
            'members.traveler',
            'payments',
            'claims',
            'trip',
            'nominees',
            'documents',
        ]);
    }

    public function cancel(Policy $policy): Policy
    {
        $policy->update(['status' => \App\Enums\PolicyStatus::Cancelled]);
        return $policy->refresh();
    }

    public function renew(Policy $policy, array $data): Policy
    {
        $newPolicy = $policy->replicate(['policy_number', 'issued_at', 'status', 'payment_status']);
        $newPolicy->policy_number  = 'TI-' . now()->format('Ymd') . '-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(8));
        $newPolicy->start_date     = $data['start_date'];
        $newPolicy->end_date       = $data['end_date'];
        $newPolicy->status         = \App\Enums\PolicyStatus::PendingPayment;
        $newPolicy->payment_status = \App\Enums\PaymentStatus::Pending;
        $newPolicy->issued_at      = null;
        $newPolicy->save();

        // Copy members
        foreach ($policy->members as $member) {
            $newPolicy->members()->create($member->only(['traveler_id', 'relationship', 'coverage_amount', 'premium']));
        }

        return $newPolicy->load(['customer', 'plan', 'members.traveler']);
    }
}
