<?php

namespace App\Repositories;

use App\Models\Claim;
use App\Enums\ClaimStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClaimRepository
{
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = Claim::with(['policy', 'traveler'])->latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['policy_id'])) {
            $query->where('policy_id', $filters['policy_id']);
        }

        return $query->paginate($perPage);
    }

    public function updateStatus(Claim $claim, ClaimStatus $status, ?float $amountApproved = null, ?string $remarks = null): Claim
    {
        $data = ['status' => $status];

        if ($amountApproved !== null) {
            $data['amount_approved'] = $amountApproved;
        }

        if ($remarks !== null) {
            $data['remarks'] = $remarks;
        }

        $claim->update($data);
        return $claim->refresh();
    }
}
