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
    
    // Check calendar availability
    Route::get('/availability', [FacebookLeadFormController::class, 'getAllLeads'])->defaults('availability', true);
    
    // Reschedule existing appointment
    Route::post('/reschedule', [FacebookLeadFormController::class, 'getAllLeads'])->defaults('reschedule', true);
    
    // Get client's appointments by name, phone or email
    Route::get('/client', [FacebookLeadFormController::class, 'getAllLeads'])->defaults('client_lookup', true);
    
    // Get appointments by UUID
    Route::get('/{uuid}', [FacebookLeadFormController::class, 'getAppointmentByUuid']);
    
    // Update appointment status
    Route::patch('/{uuid}/status', [FacebookLeadFormController::class, 'updateAppointmentStatus']);
    
    // Delete appointment (soft delete)
    Route::delete('/{uuid}', [FacebookLeadFormController::class, 'deleteAppointment']);
});

// Call Records API Routes
Route::prefix('call-records')->group(function () {
    Route::get('/', [\App\Http\Controllers\CallRecordsController::class, 'getCalls']);
    Route::get('/clear-cache', [\App\Http\Controllers\CallRecordsController::class, 'clearCallRecordsCache']);
    Route::get('/{callId}', [\App\Http\Controllers\CallRecordsController::class, 'getCallDetails']);
});
