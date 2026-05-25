<?php

namespace App\Services;

use App\Enums\ClaimStatus;
use App\Models\Claim;
use Illuminate\Support\Str;

class ClaimService
{
    public function create(array $data): Claim
    {
        return Claim::create([
            ...$data,
            'claim_number' => 'CLM-'.now()->format('Ymd').'-'.Str::upper(Str::random(8)),
            'status'       => ClaimStatus::Submitted,
        ]);
    }
}
