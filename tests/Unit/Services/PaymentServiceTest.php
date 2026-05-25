<?php

namespace Tests\Unit\Services;

use App\Models\Payment;
use App\Models\Policy;
use App\Models\User;
use App\Models\Customer;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private PaymentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PaymentService();
    }

    public function test_record_successful_payment()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 1000.00,
            'status' => 'pending_payment',
            'payment_status' => 'pending',
        ]);

        $data = [
            'policy_id' => $policy->id,
            'amount' => 1000.00,
            'payment_method' => 'credit_card',
            'transaction_id' => 'TXN-001',
            'status' => 'success',
        ];

        $payment = $this->service->record($data);

        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals('success', $payment->status);
        $this->assertNotNull($payment->paid_at);
    }

    public function test_record_successful_payment_updates_policy_status()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 5000.00,
            'status' => 'pending_payment',
            'payment_status' => 'pending',
            'issued_at' => null,
        ]);

        $data = [
            'policy_id' => $policy->id,
            'amount' => 5000.00,
            'payment_method' => 'bank_transfer',
            'transaction_id' => 'TXN-002',
            'status' => 'success',
        ];

        $payment = $this->service->record($data);

        $policy->refresh();

        $this->assertEquals('paid', $policy->payment_status);
        $this->assertEquals('active', $policy->status);
        $this->assertNotNull($policy->issued_at);
    }

    public function test_record_failed_payment()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 1500.00,
            'status' => 'pending_payment',
            'payment_status' => 'pending',
        ]);

        $data = [
            'policy_id' => $policy->id,
            'amount' => 1500.00,
            'payment_method' => 'credit_card',
            'transaction_id' => 'TXN-FAILED',
            'status' => 'failed',
        ];

        $payment = $this->service->record($data);

        $this->assertEquals('failed', $payment->status);
        $this->assertNull($payment->paid_at);
    }

    public function test_record_failed_payment_does_not_update_policy()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 2000.00,
            'status' => 'pending_payment',
            'payment_status' => 'pending',
        ]);

        $originalStatus = $policy->status;
        $originalPaymentStatus = $policy->payment_status;

        $data = [
            'policy_id' => $policy->id,
            'amount' => 2000.00,
            'payment_method' => 'credit_card',
            'transaction_id' => 'TXN-FAIL-001',
            'status' => 'failed',
        ];

        $this->service->record($data);

        $policy->refresh();

        $this->assertEquals($originalStatus, $policy->status);
        $this->assertEquals($originalPaymentStatus, $policy->payment_status);
        $this->assertNull($policy->issued_at);
    }

    public function test_record_payment_without_explicit_status_defaults_to_success()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 3000.00,
            'status' => 'pending_payment',
            'payment_status' => 'pending',
        ]);

        $data = [
            'policy_id' => $policy->id,
            'amount' => 3000.00,
            'payment_method' => 'debit_card',
            'transaction_id' => 'TXN-003',
        ];

        $payment = $this->service->record($data);

        $this->assertEquals('success', $payment->status);
        $this->assertNotNull($payment->paid_at);

        $policy->refresh();
        $this->assertEquals('active', $policy->status);
    }

    public function test_record_payment_is_transactional()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 4000.00,
            'status' => 'pending_payment',
            'payment_status' => 'pending',
        ]);

        $data = [
            'policy_id' => $policy->id,
            'amount' => 4000.00,
            'payment_method' => 'wallet',
            'transaction_id' => 'TXN-004',
            'status' => 'success',
        ];

        $payment = $this->service->record($data);

        // Both payment and policy should be created/updated
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'success',
        ]);

        $this->assertDatabaseHas('policies', [
            'id' => $policy->id,
            'payment_status' => 'paid',
            'status' => 'active',
        ]);
    }

    public function test_record_payment_loads_policy_relationship()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 1000.00,
        ]);

        $data = [
            'policy_id' => $policy->id,
            'amount' => 1000.00,
            'payment_method' => 'credit_card',
            'transaction_id' => 'TXN-005',
            'status' => 'success',
        ];

        $payment = $this->service->record($data);

        $this->assertNotNull($payment->policy);
        $this->assertEquals($policy->id, $payment->policy->id);
    }

    public function test_record_multiple_payments_for_policy()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 6000.00,
            'status' => 'pending_payment',
            'payment_status' => 'pending',
        ]);

        $data1 = [
            'policy_id' => $policy->id,
            'amount' => 3000.00,
            'payment_method' => 'credit_card',
            'transaction_id' => 'TXN-PART-001',
            'status' => 'success',
        ];

        $data2 = [
            'policy_id' => $policy->id,
            'amount' => 3000.00,
            'payment_method' => 'credit_card',
            'transaction_id' => 'TXN-PART-002',
            'status' => 'success',
        ];

        $payment1 = $this->service->record($data1);
        $payment2 = $this->service->record($data2);

        $this->assertEquals(2, $policy->payments()->count());
        $this->assertEquals(6000.00, $policy->payments()->sum('amount'));
    }

    public function test_record_payment_sets_paid_at_timestamp_for_successful_payment()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 1000.00,
        ]);

        $beforeTime = now();

        $data = [
            'policy_id' => $policy->id,
            'amount' => 1000.00,
            'payment_method' => 'netbanking',
            'transaction_id' => 'TXN-006',
            'status' => 'success',
        ];

        $payment = $this->service->record($data);

        $afterTime = now();

        $this->assertNotNull($payment->paid_at);
        $this->assertTrue($payment->paid_at->between($beforeTime, $afterTime));
    }
}
