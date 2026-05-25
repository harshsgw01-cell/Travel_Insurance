<?php

namespace Tests\Feature\Payment;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Policy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
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
        $response = $this->getJson('/api/payments');

        $response->assertStatus(401);
    }

    public function test_index_returns_payments()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        Payment::factory()->count(3)->create(['policy_id' => $policy->id]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/payments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'policy_id',
                            'amount',
                            'status',
                        ],
                    ],
                    'links',
                    'meta',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Payments fetched successfully',
            ]);
    }

    public function test_index_returns_paginated_results()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        Payment::factory()->count(20)->create(['policy_id' => $policy->id]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/payments');

        $data = $response->json('data');
        $this->assertCount(15, $data['data']);
    }

    public function test_store_records_successful_payment()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 5000.00,
            'status' => 'pending_payment',
            'payment_status' => 'pending',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/payments', [
                'policy_id' => $policy->id,
                'amount' => 5000.00,
                'payment_method' => 'credit_card',
                'transaction_id' => 'TXN-001',
                'status' => 'success',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'policy_id',
                    'amount',
                    'payment_method',
                    'status',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Payment recorded successfully',
            ]);

        $this->assertDatabaseHas('payments', [
            'policy_id' => $policy->id,
            'amount' => 5000.00,
            'status' => 'success',
        ]);
    }

    public function test_store_validates_required_fields()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/payments', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'policy_id',
                'amount',
                'payment_method',
                'transaction_id',
            ]);
    }

    public function test_store_validates_policy_exists()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/payments', [
                'policy_id' => 9999,
                'amount' => 1000.00,
                'payment_method' => 'credit_card',
                'transaction_id' => 'TXN-INVALID',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('policy_id');
    }

    public function test_store_validates_amount_is_positive()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/payments', [
                'policy_id' => $policy->id,
                'amount' => -1000.00,
                'payment_method' => 'credit_card',
                'transaction_id' => 'TXN-NEG',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('amount');
    }

    public function test_store_validates_valid_payment_method()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/payments', [
                'policy_id' => $policy->id,
                'amount' => 1000.00,
                'payment_method' => 'invalid_method',
                'transaction_id' => 'TXN-INV-METHOD',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('payment_method');
    }

    public function test_store_activates_policy_on_successful_payment()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 3000.00,
            'status' => 'pending_payment',
            'payment_status' => 'pending',
        ]);

        $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/payments', [
                'policy_id' => $policy->id,
                'amount' => 3000.00,
                'payment_method' => 'bank_transfer',
                'transaction_id' => 'TXN-002',
                'status' => 'success',
            ]);

        $policy->refresh();

        $this->assertEquals('active', $policy->status);
        $this->assertEquals('paid', $policy->payment_status);
        $this->assertNotNull($policy->issued_at);
    }

    public function test_store_records_failed_payment()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 2000.00,
            'status' => 'pending_payment',
            'payment_status' => 'pending',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/payments', [
                'policy_id' => $policy->id,
                'amount' => 2000.00,
                'payment_method' => 'credit_card',
                'transaction_id' => 'TXN-FAILED',
                'status' => 'failed',
            ]);

        $response->assertStatus(201);

        $policy->refresh();

        $this->assertEquals('pending_payment', $policy->status);
        $this->assertEquals('pending', $policy->payment_status);
        $this->assertNull($policy->issued_at);
    }

    public function test_store_without_authentication()
    {
        $customer = Customer::factory()->create();
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);

        $response = $this->postJson('/api/payments', [
            'policy_id' => $policy->id,
            'amount' => 1000.00,
            'payment_method' => 'credit_card',
            'transaction_id' => 'TXN-NO-AUTH',
        ]);

        $response->assertStatus(401);
    }

    public function test_multiple_partial_payments()
    {
        $customer = Customer::factory()->create(['user_id' => $this->user->id]);
        $policy = Policy::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 6000.00,
        ]);

        $response1 = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/payments', [
                'policy_id' => $policy->id,
                'amount' => 3000.00,
                'payment_method' => 'credit_card',
                'transaction_id' => 'TXN-PART-001',
                'status' => 'success',
            ]);

        $response2 = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/payments', [
                'policy_id' => $policy->id,
                'amount' => 3000.00,
                'payment_method' => 'credit_card',
                'transaction_id' => 'TXN-PART-002',
                'status' => 'success',
            ]);

        $response1->assertStatus(201);
        $response2->assertStatus(201);

        $this->assertEquals(2, $policy->payments()->count());
        $this->assertEquals(6000.00, $policy->payments()->sum('amount'));
    }
}
