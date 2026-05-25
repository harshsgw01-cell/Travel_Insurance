<?php

namespace App\Enums;

enum PolicyStatus: string
{
    case Draft          = 'draft';
    case PendingPayment = 'pending_payment';
    case Active         = 'active';
    case Expired        = 'expired';
    case Cancelled      = 'cancelled';
    case Claimed        = 'claimed';
    case Renewed        = 'renewed';

    public function label(): string
    {
        return match($this) {
            self::Draft          => 'Draft',
            self::PendingPayment => 'Pending Payment',
            self::Active         => 'Active',
            self::Expired        => 'Expired',
            self::Cancelled      => 'Cancelled',
            self::Claimed        => 'Claimed',
            self::Renewed        => 'Renewed',
        };
    }
}
