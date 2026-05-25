<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlanRequest;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Traits\ApiResponse;

class PlanController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success('Plans fetched successfully', PlanResource::collection(
            Plan::where('is_active', true)->latest()->paginate()
        ));
    }

    public function store(StorePlanRequest $request)
    {
        $plan = Plan::create($request->validated());

        return $this->success('Plan created successfully', new PlanResource($plan), 201);
    }

    public function show(Plan $plan)
    {
        return $this->success('Plan fetched successfully', new PlanResource($plan));
    }
}
