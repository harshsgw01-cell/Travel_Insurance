<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Policy;
use App\Traits\ApiResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function admin()
    {
        return $this->success('Dashboard metrics fetched successfully', [
            'customers' => Customer::count(),
            'policies' => Policy::count(),
            'active_policies' => Policy::where('status', 'active')->count(),
            'pending_claims' => Claim::where('status', 'submitted')->count(),
            'revenue' => Payment::where('status', 'success')->sum('amount'),
        ]);
    }
}
