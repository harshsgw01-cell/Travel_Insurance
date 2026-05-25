<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function record(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $payment = Payment::create([
                ...$data,
                'status' => $data['status'] ?? 'success',
                'paid_at' => ($data['status'] ?? 'success') === 'success' ? now() : null,
            ]);

            if ($payment->status === 'success') {
                $payment->policy()->update([
                    'payment_status' => 'paid',
                    'status' => 'active',
                    'issued_at' => now(),
                ]);
            }

            return $payment->load('policy');
        });
    }
}
