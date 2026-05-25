<?php

namespace Tests\Unit\Services;

use App\Models\Claim;
use App\Models\Policy;
use App\Models\Traveler;
use App\Models\User;
use App\Models\Customer;
use App\Services\ClaimService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClaimServiceTest extends TestCase
{
    use RefreshDatabase;

    private ClaimService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ClaimService();
    }

    public function test_create_claim_with_valid_data()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        $traveler = Traveler::factory()->create();

        $data = [
            'policy_id' => $policy->id,
            'traveler_id' => $traveler->id,
            'claim_type' => 'Medical Emergency',
            'incident_date' => now()->subDays(5)->toDateString(),
            'amount_claimed' => 5000.00,
            'amount_approved' => null,
            'remarks' => 'Hospital admission for fever',
        ];

        $claim = $this->service->create($data);

        $this->assertInstanceOf(Claim::class, $claim);
        $this->assertEquals('CLM-' . now()->format('Ymd'), substr($claim->claim_number, 0, 10));
        $this->assertEquals('submitted', $claim->status);
        $this->assertEquals($policy->id, $claim->policy_id);
        $this->assertEquals($traveler->id, $claim->traveler_id);
        $this->assertEquals('Medical Emergency', $claim->claim_type);
    }

    public function test_create_claim_generates_unique_claim_number()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        $traveler = Traveler::factory()->create();

        $data1 = [
            'policy_id' => $policy->id,
            'traveler_id' => $traveler->id,
            'claim_type' => 'Medical Emergency',
            'incident_date' => now()->subDays(5)->toDateString(),
            'amount_claimed' => 5000.00,
            'remarks' => 'Hospital admission',
        ];

        $data2 = $data1;
        $traveler2 = Traveler::factory()->create();
        $data2['traveler_id'] = $traveler2->id;

        $claim1 = $this->service->create($data1);
        $claim2 = $this->service->create($data2);

        $this->assertNotEquals($claim1->claim_number, $claim2->claim_number);
    }

    public function test_create_claim_sets_submitted_status()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        $traveler = Traveler::factory()->create();

        $data = [
            'policy_id' => $policy->id,
            'traveler_id' => $traveler->id,
            'claim_type' => 'Baggage Loss',
            'incident_date' => now()->subDays(2)->toDateString(),
            'amount_claimed' => 1500.00,
            'remarks' => 'Baggage lost at airport',
        ];

        $claim = $this->service->create($data);

        $this->assertEquals('submitted', $claim->status);
        $this->assertNull($claim->amount_approved);
    }

    public function test_create_claim_with_all_fields()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        $traveler = Traveler::factory()->create();

        $data = [
            'policy_id' => $policy->id,
            'traveler_id' => $traveler->id,
            'claim_type' => 'Trip Cancellation',
            'incident_date' => now()->toDateString(),
            'amount_claimed' => 10000.00,
            'amount_approved' => 8000.00,
            'remarks' => 'Trip cancelled due to emergency',
        ];

        $claim = $this->service->create($data);

        $this->assertEquals(10000.00, $claim->amount_claimed);
        $this->assertEquals(8000.00, $claim->amount_approved);
        $this->assertEquals('Trip Cancellation', $claim->claim_type);
    }

    public function test_claim_number_contains_date_and_random_string()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        $traveler = Traveler::factory()->create();

        $data = [
            'policy_id' => $policy->id,
            'traveler_id' => $traveler->id,
            'claim_type' => 'Medical',
            'incident_date' => now()->subDays(1)->toDateString(),
            'amount_claimed' => 2000.00,
            'remarks' => 'Test claim',
        ];

        $claim = $this->service->create($data);

        $this->assertStringContainsString('CLM-', $claim->claim_number);
        $this->assertStringContainsString(now()->format('Ymd'), $claim->claim_number);
        // Format: CLM-YYYYMMDD-XXXXXXXX
        $this->assertStringMatchesFormat('CLM-%d-%*', $claim->claim_number);
    }

    public function test_create_multiple_claims_for_same_policy()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        $traveler1 = Traveler::factory()->create();
        $traveler2 = Traveler::factory()->create();

        $data1 = [
            'policy_id' => $policy->id,
            'traveler_id' => $traveler1->id,
            'claim_type' => 'Medical',
            'incident_date' => now()->subDays(5)->toDateString(),
            'amount_claimed' => 2000.00,
            'remarks' => 'First claim',
        ];

        $data2 = [
            'policy_id' => $policy->id,
            'traveler_id' => $traveler2->id,
            'claim_type' => 'Baggage',
            'incident_date' => now()->subDays(3)->toDateString(),
            'amount_claimed' => 1000.00,
            'remarks' => 'Second claim',
        ];

        $claim1 = $this->service->create($data1);
        $claim2 = $this->service->create($data2);

        $this->assertEquals(2, $policy->claims()->count());
        $this->assertTrue($policy->claims()->pluck('id')->contains($claim1->id));
        $this->assertTrue($policy->claims()->pluck('id')->contains($claim2->id));
    }
}
