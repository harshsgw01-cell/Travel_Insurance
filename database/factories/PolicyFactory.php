<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\Policy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Policy>
 */
class PolicyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $premium = $this->faker?->numberBetween(1000, 50000) ?? 5000;
        $taxAmount = round($premium * 0.18, 2);

        return [
            'policy_number' => 'TI-' . now()->format('Ymd') . '-' . strtoupper(str_pad(rand(1000000, 9999999), 8, '0', STR_PAD_LEFT)),
            'customer_id' => Customer::factory(),
            'plan_id' => Plan::factory(),
            'agent_id' => null,
            'policy_type' => $this->faker?->randomElement(['Individual', 'Family', 'Group']) ?? 'Individual',
            'start_date' => $this->faker?->dateTimeBetween('now', '+1 year') ?? now(),
            'end_date' => $this->faker?->dateTimeBetween('+30 days', '+1 year') ?? now()->addDays(30),
            'destination_country' => $this->faker?->country() ?? 'USA',
            'trip_type' => $this->faker?->randomElement(['Business', 'Tourism', 'Medical', 'Education']) ?? 'Tourism',
            'premium_amount' => $premium,
            'tax_amount' => $taxAmount,
            'total_amount' => $premium + $taxAmount,
            'status' => $this->faker?->randomElement(['pending_payment', 'active', 'expired', 'cancelled']) ?? 'pending_payment',
            'payment_status' => $this->faker?->randomElement(['pending', 'paid', 'failed']) ?? 'pending',
            'issued_at' => $this->faker?->dateTimeThisMonth() ?? now(),
        ];
    }
}
