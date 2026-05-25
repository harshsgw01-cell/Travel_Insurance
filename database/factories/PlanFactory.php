<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $uniqueId = uniqid();
        return [
            'name' => $this->faker?->words(3, true) ?? 'Travel Insurance Plan',
            'code' => strtoupper("PLAN{$uniqueId}"),
            'policy_type' => $this->faker?->randomElement(['Individual', 'Family', 'Group']) ?? 'Individual',
            'base_premium' => $this->faker?->numberBetween(1000, 10000) ?? 5000,
            'coverage_amount' => $this->faker?->numberBetween(100000, 1000000) ?? 500000,
            'max_age' => $this->faker?->numberBetween(60, 75) ?? 70,
            'min_age' => $this->faker?->numberBetween(5, 18) ?? 10,
            'max_family_members' => 5,
            'covered_countries' => json_encode(['USA', 'UK', 'Canada', 'Australia']),
            'benefits' => json_encode(['medical', 'baggage', 'trip_cancellation']),
            'add_ons' => json_encode(['adventure_sports', 'business_travel']),
            'is_active' => true,
        ];
    }
}
