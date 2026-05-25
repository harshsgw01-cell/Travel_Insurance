<?php

namespace Database\Factories;

use App\Models\Claim;
use App\Models\Policy;
use App\Models\Traveler;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Claim>
 */
class ClaimFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amountClaimed = $this->faker?->numberBetween(1000, 100000) ?? 5000;
        $amountApproved = rand(0, 1) ? $amountClaimed * 0.8 : null;

        return [
            'policy_id' => Policy::factory(),
            'claim_number' => 'CLM-' . now()->format('Ymd') . '-' . strtoupper(str_pad(rand(1000000, 9999999), 8, '0', STR_PAD_LEFT)),
            'traveler_id' => Traveler::factory(),
            'claim_type' => $this->faker?->randomElement(['Medical Emergency', 'Baggage Loss', 'Trip Cancellation', 'Flight Delay']) ?? 'Medical Emergency',
            'incident_date' => $this->faker?->dateTimeBetween('-90 days', 'now') ?? now()->subDays(30),
            'amount_claimed' => $amountClaimed,
            'amount_approved' => $amountApproved,
            'status' => $this->faker?->randomElement(['submitted', 'under_review', 'approved', 'rejected']) ?? 'submitted',
            'remarks' => $this->faker?->sentence() ?? 'Claim remark',
        ];
    }
}
