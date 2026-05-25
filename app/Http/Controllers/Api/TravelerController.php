<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTravelerRequest;
use App\Http\Resources\TravelerResource;
use App\Models\Traveler;
use App\Traits\ApiResponse;

class TravelerController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success('Travelers fetched successfully', TravelerResource::collection(
            Traveler::with(['customer', 'familyMember'])->latest()->paginate()
        ));
    }

    public function store(StoreTravelerRequest $request)
    {
        $traveler = Traveler::create($request->validated());

        return $this->success('Traveler created successfully', new TravelerResource($traveler), 201);
    }
}
