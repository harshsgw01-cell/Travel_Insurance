<?php

namespace Tests\Feature\Policy;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\Policy;
use App\Models\Traveler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('api')->plainTextToken;
    }

    public function test_index_requires_authentication()
    {
        $response = $this->getJson('/api/policies');

        $response->assertStatus(401);
    }

    public function test_index_returns_policies()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        Policy::factory()->count(3)->create(['customer_id' => $customer->id]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/policies');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'policy_number',
                            'status',
                            'payment_status',
                        ],
                    ],
                    'links',
                    'meta',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Policies fetched successfully',
            ]);
    }

    public function test_index_returns_paginated_results()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        Policy::factory()->count(20)->create(['customer_id' => $customer->id]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/policies');

        $data = $response->json('data');
        $this->assertCount(15, $data['data']);
        $this->assertNotNull($data['links']);
        $this->assertNotNull($data['meta']);
    }

    public function test_store_creates_policy_with_valid_data()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $plan = Plan::factory()->create();
        $travelers = Traveler::factory()->count(2)->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/policies', [
                'customer_id' => $customer->id,
                'plan_id' => $plan->id,
                'agent_id' => null,
                'policy_type' => 'Family',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
                'destination_country' => 'USA',
                'trip_type' => 'Vacation',
                'premium_amount' => 5000.00,
                'tax_amount' => null,
                'traveler_ids' => $travelers->pluck('id')->toArray(),
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'policy_number',
                    'customer_id',
                    'plan_id',
                    'status',
                    'payment_status',
                    'total_amount',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Policy created successfully',
            ]);

        $this->assertDatabaseHas('policies', [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
        ]);
    }

    public function test_store_validates_required_fields()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/policies', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'customer_id',
                'plan_id',
                'policy_type',
                'start_date',
                'end_date',
                'destination_country',
                'premium_amount',
                'traveler_ids',
            ]);
    }

    public function test_store_validates_customer_exists()
    {
        $plan = Plan::factory()->create();
        $travelers = Traveler::factory()->count(1)->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/policies', [
                'customer_id' => 9999,
                'plan_id' => $plan->id,
                'policy_type' => 'Individual',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
                'destination_country' => 'USA',
                'premium_amount' => 1000.00,
                'traveler_ids' => $travelers->pluck('id')->toArray(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('customer_id');
    }

    public function test_store_validates_plan_exists()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $travelers = Traveler::factory()->count(1)->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/policies', [
                'customer_id' => $customer->id,
                'plan_id' => 9999,
                'policy_type' => 'Individual',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
                'destination_country' => 'USA',
                'premium_amount' => 1000.00,
                'traveler_ids' => $travelers->pluck('id')->toArray(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('plan_id');
    }

    public function test_store_validates_end_date_after_start_date()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $plan = Plan::factory()->create();
        $travelers = Traveler::factory()->count(1)->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/policies', [
                'customer_id' => $customer->id,
                'plan_id' => $plan->id,
                'policy_type' => 'Individual',
                'start_date' => now()->addDays(10)->toDateString(),
                'end_date' => now()->toDateString(),
                'destination_country' => 'USA',
                'premium_amount' => 1000.00,
                'traveler_ids' => $travelers->pluck('id')->toArray(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('end_date');
    }

    public function test_store_validates_traveler_ids_not_empty()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $plan = Plan::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/policies', [
                'customer_id' => $customer->id,
                'plan_id' => $plan->id,
                'policy_type' => 'Individual',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
                'destination_country' => 'USA',
                'premium_amount' => 1000.00,
                'traveler_ids' => [],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('traveler_ids');
    }

    public function test_show_returns_policy_details()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson("/api/policies/{$policy->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'policy_number',
                    'customer_id',
                    'plan_id',
                    'status',
                    'payment_status',
                    'total_amount',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Policy fetched successfully',
                'data' => [
                    'id' => $policy->id,
                    'policy_number' => $policy->policy_number,
                ],
            ]);
    }

    public function test_show_with_nonexistent_policy()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/policies/9999');

        $response->assertStatus(404);
    }

    public function test_store_without_authentication()
    {
        $customer = Customer::factory()->create();
        $plan = Plan::factory()->create();
        $travelers = Traveler::factory()->count(1)->create();

        $response = $this->postJson('/api/policies', [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'policy_type' => 'Individual',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'destination_country' => 'USA',
            'premium_amount' => 1000.00,
            'traveler_ids' => $travelers->pluck('id')->toArray(),
        ]);

        $response->assertStatus(401);
    }
}
