<?php

namespace Tests\Unit\Services;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\Policy;
use App\Models\Traveler;
use App\Models\User;
use App\Services\PolicyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyServiceTest extends TestCase
{
    use RefreshDatabase;

    private PolicyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PolicyService();
    }

    public function test_create_policy_with_valid_data()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $plan = Plan::factory()->create();
        $travelers = Traveler::factory()->count(2)->create();

        $data = [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'agent_id' => null,
            'policy_type' => 'Individual',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'destination_country' => 'USA',
            'trip_type' => 'Business',
            'premium_amount' => 1000.00,
            'tax_amount' => null,
            'traveler_ids' => $travelers->pluck('id')->toArray(),
        ];

        $policy = $this->service->create($data);

        $this->assertInstanceOf(Policy::class, $policy);
        $this->assertEquals('TI-' . now()->format('Ymd'), substr($policy->policy_number, 0, 10));
        $this->assertEquals(180.00, $policy->tax_amount);
        $this->assertEquals(1180.00, $policy->total_amount);
        $this->assertEquals('pending_payment', $policy->status);
        $this->assertEquals('pending', $policy->payment_status);
        $this->assertEquals(2, $policy->members()->count());
    }

    public function test_create_policy_calculates_correct_tax()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $plan = Plan::factory()->create();
        $travelers = Traveler::factory()->count(1)->create();

        $data = [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'agent_id' => null,
            'policy_type' => 'Individual',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'destination_country' => 'USA',
            'trip_type' => 'Tourism',
            'premium_amount' => 5000.00,
            'tax_amount' => null,
            'traveler_ids' => $travelers->pluck('id')->toArray(),
        ];

        $policy = $this->service->create($data);

        // 5000 * 0.18 = 900
        $this->assertEquals(900.00, $policy->tax_amount);
        $this->assertEquals(5900.00, $policy->total_amount);
    }

    public function test_create_policy_with_custom_tax_amount()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $plan = Plan::factory()->create();
        $travelers = Traveler::factory()->count(1)->create();

        $data = [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'agent_id' => null,
            'policy_type' => 'Individual',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'destination_country' => 'USA',
            'trip_type' => 'Tourism',
            'premium_amount' => 5000.00,
            'tax_amount' => 500.00,
            'traveler_ids' => $travelers->pluck('id')->toArray(),
        ];

        $policy = $this->service->create($data);

        $this->assertEquals(500.00, $policy->tax_amount);
        $this->assertEquals(5500.00, $policy->total_amount);
    }

    public function test_create_policy_divides_premium_among_travelers()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $plan = Plan::factory()->create();
        $travelers = Traveler::factory()->count(3)->create();

        $data = [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'agent_id' => null,
            'policy_type' => 'Family',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'destination_country' => 'USA',
            'trip_type' => 'Tourism',
            'premium_amount' => 3000.00,
            'tax_amount' => null,
            'traveler_ids' => $travelers->pluck('id')->toArray(),
        ];

        $policy = $this->service->create($data);

        $this->assertEquals(3, $policy->members()->count());
        // 3000 / 3 = 1000
        $policy->members()->each(function ($member) {
            $this->assertEquals(1000.00, $member->premium);
        });
    }

    public function test_create_policy_with_single_traveler()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $plan = Plan::factory()->create();
        $traveler = Traveler::factory()->create();

        $data = [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'agent_id' => null,
            'policy_type' => 'Individual',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'destination_country' => 'USA',
            'trip_type' => 'Tourism',
            'premium_amount' => 2500.00,
            'tax_amount' => null,
            'traveler_ids' => [$traveler->id],
        ];

        $policy = $this->service->create($data);

        $this->assertEquals(1, $policy->members()->count());
        $this->assertEquals(2500.00, $policy->members()->first()->premium);
    }

    public function test_create_policy_loads_relationships()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $plan = Plan::factory()->create();
        $travelers = Traveler::factory()->count(1)->create();

        $data = [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'agent_id' => null,
            'policy_type' => 'Individual',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'destination_country' => 'USA',
            'trip_type' => 'Tourism',
            'premium_amount' => 1000.00,
            'tax_amount' => null,
            'traveler_ids' => $travelers->pluck('id')->toArray(),
        ];

        $policy = $this->service->create($data);

        $this->assertNotNull($policy->customer);
        $this->assertNotNull($policy->plan);
        $this->assertNotEmpty($policy->members);
        $this->assertNotNull($policy->members->first()->traveler);
    }

    public function test_create_policy_is_transactional()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $plan = Plan::factory()->create();
        $travelers = Traveler::factory()->count(2)->create();

        $initialPolicyCount = Policy::count();

        $data = [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'agent_id' => null,
            'policy_type' => 'Individual',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'destination_country' => 'USA',
            'trip_type' => 'Tourism',
            'premium_amount' => 1000.00,
            'tax_amount' => null,
            'traveler_ids' => $travelers->pluck('id')->toArray(),
        ];

        $policy = $this->service->create($data);

        // Policy and members should be created together
        $this->assertEquals($initialPolicyCount + 1, Policy::count());
        $this->assertEquals(2, $policy->members()->count());
    }

    public function test_create_policy_with_agent()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $agent = User::factory()->create();
        $plan = Plan::factory()->create();
        $travelers = Traveler::factory()->count(1)->create();

        $data = [
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'agent_id' => $agent->id,
            'policy_type' => 'Individual',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'destination_country' => 'USA',
            'trip_type' => 'Tourism',
            'premium_amount' => 1000.00,
            'tax_amount' => null,
            'traveler_ids' => $travelers->pluck('id')->toArray(),
        ];

        $policy = $this->service->create($data);

        $this->assertEquals($agent->id, $policy->agent_id);
    }

    public function test_policy_number_is_unique()
    {
        $user = User::factory()->create();
        $customer1 = Customer::factory()->create(['user_id' => $user->id]);
        $customer2 = Customer::factory()->create(['user_id' => $user->id]);
        $plan = Plan::factory()->create();
        $travelers = Traveler::factory()->count(1)->create();

        $data1 = [
            'customer_id' => $customer1->id,
            'plan_id' => $plan->id,
            'agent_id' => null,
            'policy_type' => 'Individual',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'destination_country' => 'USA',
            'trip_type' => 'Tourism',
            'premium_amount' => 1000.00,
            'tax_amount' => null,
            'traveler_ids' => $travelers->pluck('id')->toArray(),
        ];

        $data2 = $data1;
        $data2['customer_id'] = $customer2->id;

        $policy1 = $this->service->create($data1);
        $policy2 = $this->service->create($data2);

        $this->assertNotEquals($policy1->policy_number, $policy2->policy_number);
    }
}
