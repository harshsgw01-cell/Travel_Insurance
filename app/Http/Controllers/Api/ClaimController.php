<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClaimRequest;
use App\Http\Resources\ClaimResource;
use App\Models\Claim;
use App\Services\ClaimService;
use App\Traits\ApiResponse;

class ClaimController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success('Claims fetched successfully', ClaimResource::collection(
            Claim::with(['policy', 'traveler'])->latest()->paginate()
        ));
    }

    public function store(StoreClaimRequest $request, ClaimService $service)
    {
        $claim = $service->create($request->validated());

        return $this->success('Claim submitted successfully', new ClaimResource($claim), 201);
    }
}
