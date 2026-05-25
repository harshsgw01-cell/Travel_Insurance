<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\PolicyPaymentStatus;
use App\Enums\PolicyStatus;
use App\Events\PolicyIssued;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function record(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $payment = Payment::create([
                ...$data,
                'status'  => $data['status'] ?? PaymentStatus::Success->value,
                'paid_at' => ($data['status'] ?? PaymentStatus::Success->value) === PaymentStatus::Success->value ? now() : null,
            ]);

            if ($payment->status === PaymentStatus::Success) {
                $payment->policy()->update([
                    'payment_status' => PolicyPaymentStatus::Paid,
                    'status'         => PolicyStatus::Active,
                    'issued_at'      => now(),
                ]);

                PolicyIssued::dispatch($payment->policy()->first());
            }

            return $payment->load('policy');
        });
    }
}
