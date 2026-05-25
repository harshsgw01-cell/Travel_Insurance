<?php

namespace App\Enums;

enum ClaimStatus: string
{
    case Submitted    = 'submitted';
    case UnderReview  = 'under_review';
    case Approved     = 'approved';
    case Rejected     = 'rejected';
    case Settled      = 'settled';

    public function label(): string
    {
        return match($this) {
            self::Submitted   => 'Submitted',
            self::UnderReview => 'Under Review',
            self::Approved    => 'Approved',
            self::Rejected    => 'Rejected',
            self::Settled     => 'Settled',
        };
    }
}
