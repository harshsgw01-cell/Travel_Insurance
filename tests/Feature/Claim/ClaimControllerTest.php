<?php

namespace Tests\Feature\Claim;

use App\Models\Claim;
use App\Models\Customer;
use App\Models\Policy;
use App\Models\Traveler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClaimControllerTest extends TestCase
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
        $response = $this->getJson('/api/claims');

        $response->assertStatus(401);
    }

    public function test_index_returns_claims()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        Claim::factory()->count(3)->create(['policy_id' => $policy->id]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/claims');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'claim_number',
                            'claim_type',
                            'status',
                        ],
                    ],
                    'links',
                    'meta',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Claims fetched successfully',
            ]);
    }

    public function test_index_returns_paginated_results()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        Claim::factory()->count(20)->create(['policy_id' => $policy->id]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/claims');

        $data = $response->json('data');
        $this->assertCount(15, $data['data']);
    }

    public function test_store_creates_claim_with_valid_data()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        $traveler = Traveler::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/claims', [
                'policy_id' => $policy->id,
                'traveler_id' => $traveler->id,
                'claim_type' => 'Medical Emergency',
                'incident_date' => now()->subDays(5)->toDateString(),
                'amount_claimed' => 5000.00,
                'remarks' => 'Hospital admission for fever',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'claim_number',
                    'policy_id',
                    'traveler_id',
                    'claim_type',
                    'status',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Claim created successfully',
            ]);

        $this->assertDatabaseHas('claims', [
            'policy_id' => $policy->id,
            'traveler_id' => $traveler->id,
            'claim_type' => 'Medical Emergency',
        ]);
    }

    public function test_store_validates_required_fields()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/claims', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'policy_id',
                'traveler_id',
                'claim_type',
                'incident_date',
                'amount_claimed',
            ]);
    }

    public function test_store_validates_policy_exists()
    {
        $traveler = Traveler::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/claims', [
                'policy_id' => 9999,
                'traveler_id' => $traveler->id,
                'claim_type' => 'Baggage Loss',
                'incident_date' => now()->subDays(2)->toDateString(),
                'amount_claimed' => 1500.00,
                'remarks' => 'Baggage lost at airport',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('policy_id');
    }

    public function test_store_validates_traveler_exists()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/claims', [
                'policy_id' => $policy->id,
                'traveler_id' => 9999,
                'claim_type' => 'Baggage Loss',
                'incident_date' => now()->subDays(2)->toDateString(),
                'amount_claimed' => 1500.00,
                'remarks' => 'Baggage lost',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('traveler_id');
    }

    public function test_store_validates_amount_claimed_is_positive()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        $traveler = Traveler::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/claims', [
                'policy_id' => $policy->id,
                'traveler_id' => $traveler->id,
                'claim_type' => 'Medical',
                'incident_date' => now()->subDays(1)->toDateString(),
                'amount_claimed' => -1000.00,
                'remarks' => 'Invalid claim',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('amount_claimed');
    }

    public function test_store_without_authentication()
    {
        $customer = Customer::factory()->create();
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        $traveler = Traveler::factory()->create();

        $response = $this->postJson('/api/claims', [
            'policy_id' => $policy->id,
            'traveler_id' => $traveler->id,
            'claim_type' => 'Medical',
            'incident_date' => now()->subDays(5)->toDateString(),
            'amount_claimed' => 5000.00,
            'remarks' => 'Hospital stay',
        ]);

        $response->assertStatus(401);
    }

    public function test_multiple_claims_for_same_policy()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        $traveler1 = Traveler::factory()->create();
        $traveler2 = Traveler::factory()->create();

        $response1 = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/claims', [
                'policy_id' => $policy->id,
                'traveler_id' => $traveler1->id,
                'claim_type' => 'Medical',
                'incident_date' => now()->subDays(10)->toDateString(),
                'amount_claimed' => 2000.00,
                'remarks' => 'First claim',
            ]);

        $response2 = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/claims', [
                'policy_id' => $policy->id,
                'traveler_id' => $traveler2->id,
                'claim_type' => 'Baggage',
                'incident_date' => now()->subDays(5)->toDateString(),
                'amount_claimed' => 1000.00,
                'remarks' => 'Second claim',
            ]);

        $response1->assertStatus(201);
        $response2->assertStatus(201);

        $this->assertEquals(2, $policy->claims()->count());
    }
}
