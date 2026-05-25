<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Traits\ApiResponse;

class CustomerController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success('Customers fetched successfully', CustomerResource::collection(
            Customer::with(['familyMembers', 'policies'])->latest()->paginate()
        ));
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create([
            ...$request->validated(),
            'customer_code' => 'CUS-'.now()->format('YmdHis'),
        ]);

        return $this->success('Customer created successfully', new CustomerResource($customer), 201);
    }

    public function show(Customer $customer)
    {
        return $this->success('Customer fetched successfully', new CustomerResource(
            $customer->load(['familyMembers', 'travelers', 'policies'])
        ));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return $this->success('Customer updated successfully', new CustomerResource($customer->refresh()));
    }
}
