<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePolicyRequest;
use App\Http\Resources\PolicyResource;
use App\Models\Policy;
use App\Services\PolicyService;
use App\Traits\ApiResponse;

class PolicyController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success('Policies fetched successfully', PolicyResource::collection(
            Policy::with(['customer', 'plan', 'payments', 'claims'])->latest()->paginate()
        ));
    }

    public function store(StorePolicyRequest $request, PolicyService $service)
    {
        $policy = $service->create($request->validated());

        return $this->success('Policy created successfully', new PolicyResource($policy), 201);
    }

    public function show(Policy $policy)
    {
        return $this->success('Policy fetched successfully', new PolicyResource(
            $policy->load(['customer', 'plan', 'members.traveler', 'payments', 'claims'])
        ));
    }
}
