<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Traits\ApiResponse;

class PaymentController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success('Payments fetched successfully', PaymentResource::collection(
            Payment::with('policy')->latest()->paginate()
        ));
    }

    public function store(StorePaymentRequest $request, PaymentService $service)
    {
        $payment = $service->record($request->validated());

        return $this->success('Payment recorded successfully', new PaymentResource($payment), 201);
    }
}
