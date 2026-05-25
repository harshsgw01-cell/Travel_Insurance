<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClaimController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FamilyMemberController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\PolicyController;
use App\Http\Controllers\Api\TravelerController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard/admin', [DashboardController::class, 'admin']);

    Route::apiResource('customers', CustomerController::class)->only(['index', 'store', 'show', 'update']);
    Route::apiResource('family-members', FamilyMemberController::class)->only(['index', 'store']);
    Route::apiResource('travelers', TravelerController::class)->only(['index', 'store']);
    Route::apiResource('plans', PlanController::class)->only(['index', 'store', 'show']);
    Route::apiResource('policies', PolicyController::class)->only(['index', 'store', 'show']);
    Route::apiResource('claims', ClaimController::class)->only(['index', 'store']);
    Route::apiResource('payments', PaymentController::class)->only(['index', 'store']);
});
