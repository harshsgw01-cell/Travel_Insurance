<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Traveler>
 */
class TravelerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'family_member_id' => null,
            'first_name' => $this->faker?->firstName() ?? 'Jane',
            'last_name' => $this->faker?->lastName() ?? 'Smith',
            'dob' => $this->faker?->dateTimeBetween('-70 years', '-5 years') ?? now()->subYears(30),
            'gender' => $this->faker?->randomElement(['Male', 'Female', 'Other']) ?? 'Female',
            'passport_no' => strtoupper('PS' . rand(100000000, 999999999)),
            'nationality' => $this->faker?->country() ?? 'USA',
            'visa_type' => $this->faker?->randomElement(['Single Entry', 'Multiple Entry', 'No Visa']) ?? 'Single Entry',
            'emergency_contact' => $this->faker?->phoneNumber() ?? '+1234567890',
        ];
    }
}
