<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacebookLeadFormController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Facebook Lead API Routes
Route::prefix('facebook-leads')->group(function () {
    // Store a new lead (microservice endpoint)
    Route::post('/', [FacebookLeadFormController::class, 'storeApi']);
    
    // Get all leads with optional pagination and filtering
    Route::get('/', [FacebookLeadFormController::class, 'getAllLeads']);
    
    // You can add more endpoints here as needed
    // Route::get('/{id}', [FacebookLeadFormController::class, 'getLeadById']);
    // Route::put('/{id}', [FacebookLeadFormController::class, 'updateLead']);
    // Route::delete('/{id}', [FacebookLeadFormController::class, 'deleteLead']);
});
