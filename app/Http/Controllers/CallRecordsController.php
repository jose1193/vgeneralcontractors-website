<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RetellAIService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CallRecordsController extends Controller
{
    protected $retellService;

    public function __construct(RetellAIService $retellService)
    {
        $this->retellService = $retellService;
    }

    public function index()
    {
        return view('call-records.index');
    }

    public function getCalls(Request $request)
    {
        try {
            $filters = [];
            
            // Apply date filters if provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                
                // Validate that end date isn't before start date
                if (strtotime($endDate) < strtotime($startDate)) {
                    return response()->json([
                        'error' => 'End date cannot be before start date'
                    ], 400);
                }
                
                // Validate that dates aren't in the future
                $today = Carbon::now()->startOfDay();
                if (Carbon::parse($startDate)->startOfDay()->greaterThan($today) ||
                    Carbon::parse($endDate)->startOfDay()->greaterThan($today)) {
                    return response()->json([
                        'error' => 'Dates cannot be in the future'
                    ], 400);
                }
                
                $startTimestamp = Carbon::parse($startDate)->startOfDay()->timestamp;
                $endTimestamp = Carbon::parse($endDate)->endOfDay()->timestamp;
                
                $filters['time_range'] = [
                    'start_timestamp' => $startTimestamp,
                    'end_timestamp' => $endTimestamp
                ];
            }
            
            $calls = $this->retellService->listCalls($filters);
            
            // Apply search filter in PHP if provided
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = strtolower($request->search);
                $calls = collect($calls)->filter(function ($call) use ($searchTerm) {
                    // Search in phone numbers
                    if (isset($call['from_number']) && str_contains(strtolower($call['from_number']), $searchTerm)) {
                        return true;
                    }
                    if (isset($call['to_number']) && str_contains(strtolower($call['to_number']), $searchTerm)) {
                        return true;
                    }
                    
                    // Search in call summary
                    if (isset($call['call_analysis']) && 
                        isset($call['call_analysis']['call_summary']) && 
                        str_contains(strtolower($call['call_analysis']['call_summary']), $searchTerm)) {
                        return true;
                    }
                    
                    // Search in transcript
                    if (isset($call['transcript']) && 
                        str_contains(strtolower($call['transcript']), $searchTerm)) {
                        return true;
                    }
                    
                    // Search in call status
                    if (isset($call['call_status']) && 
                        str_contains(strtolower($call['call_status']), $searchTerm)) {
                        return true;
                    }
                    
                    // Search in sentiment
                    if (isset($call['call_analysis']) && 
                        isset($call['call_analysis']['user_sentiment']) && 
                        str_contains(strtolower($call['call_analysis']['user_sentiment']), $searchTerm)) {
                        return true;
                    }
                    
                    return false;
                })->values()->all();
                
                // Log search results
                Log::info('Search results', [
                    'term' => $searchTerm,
                    'results' => count($calls)
                ]);
            }
            
            // Apply sorting if provided
            $sortField = $request->input('sort_field', 'start_timestamp');
            $sortDirection = $request->input('sort_direction', 'desc');
            
            $calls = collect($calls)->sortBy($sortField, SORT_REGULAR, $sortDirection === 'desc')->values()->all();
            
            // Apply pagination
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);
            $total = count($calls);
            
            $paginatedCalls = array_slice($calls, ($page - 1) * $perPage, $perPage);
            
            return response()->json([
                'calls' => $paginatedCalls,
                'total' => $total,
                'current_page' => (int)$page,
                'per_page' => (int)$perPage,
                'last_page' => ceil($total / $perPage)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error loading calls', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error loading calls: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCallDetails($callId)
    {
        try {
            $call = $this->retellService->getCall($callId);
            
            if (!$call) {
                return response()->json([
                    'error' => 'Call record not found.'
                ], 404);
            }
            
            return response()->json([
                'call' => $call
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching call details', [
                'error' => $e->getMessage(),
                'call_id' => $callId
            ]);
            
            return response()->json([
                'error' => 'Error fetching call details: ' . $e->getMessage()
            ], 500);
        }
    }
} 