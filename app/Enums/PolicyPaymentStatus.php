<?php

namespace App\Enums;

enum PolicyPaymentStatus: string
{
    case Pending = 'pending';
    case Paid    = 'paid';
    case Refunded = 'refunded';
}
