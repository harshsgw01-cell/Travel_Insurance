<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
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
            'user_id' => User::factory(),
            'customer_code' => 'CUST-' . strtoupper(str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT)),
            'first_name' => $this->faker?->firstName() ?? 'John',
            'last_name' => $this->faker?->lastName() ?? 'Doe',
            'email' => $this->faker?->unique()->safeEmail() ?? "customer{$uniqueId}@example.com",
            'mobile' => $this->faker?->phoneNumber() ?? '+1234567890',
            'dob' => $this->faker?->dateTimeBetween('-70 years', '-18 years') ?? now()->subYears(25),
            'gender' => $this->faker?->randomElement(['Male', 'Female', 'Other']) ?? 'Male',
            'passport_no' => strtoupper('PS' . rand(100000000, 999999999)),
            'nationality' => $this->faker?->country() ?? 'India',
            'address' => $this->faker?->address() ?? '123 Main Street',
            'kyc_status' => 'verified',
            'status' => 'active',
        ];
    }
}
