<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Policy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker?->randomElement(['success', 'pending', 'failed']) ?? 'success';
        $paidAt = $status === 'success' ? $this->faker?->dateTimeThisMonth() ?? now() : null;

        return [
            'policy_id' => Policy::factory(),
            'amount' => $this->faker?->numberBetween(1000, 50000) ?? 5000,
            'payment_method' => $this->faker?->randomElement(['credit_card', 'debit_card', 'bank_transfer', 'wallet', 'netbanking']) ?? 'credit_card',
            'transaction_id' => 'TXN-' . strtoupper(str_pad(rand(1000000000, 9999999999), 11, '0', STR_PAD_LEFT)),
            'status' => $status,
            'paid_at' => $paidAt,
        ];
    }
}
