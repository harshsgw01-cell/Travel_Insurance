<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFamilyMemberRequest;
use App\Http\Resources\FamilyMemberResource;
use App\Models\FamilyMember;
use App\Traits\ApiResponse;

class FamilyMemberController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success('Family members fetched successfully', FamilyMemberResource::collection(
            FamilyMember::latest()->paginate()
        ));
    }

    public function store(StoreFamilyMemberRequest $request)
    {
        $familyMember = FamilyMember::create($request->validated());

        return $this->success('Family member created successfully', new FamilyMemberResource($familyMember), 201);
    }
}
