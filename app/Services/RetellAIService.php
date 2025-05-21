<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RetellAIService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.retellai.com/v2';

    public function __construct()
    {
        $this->apiKey = config('services.retellai.api_key');
    }

    /**
     * List all calls
     */
    public function listCalls($filters = [])
    {
        try {
            $payload = [
                'limit' => 100,
                'offset' => 0,
            ];
            
            // Only add filters if there are any to prevent empty filters object
            if (!empty($filters)) {
                // Log the filter structure being sent to API
                Log::info('RetellAI API Request filters', ['filters' => $filters]);
                
                // Ensure time_range filter is properly formatted
                if (isset($filters['time_range'])) {
                    $timeRange = $filters['time_range'];
                    // Make sure we have the expected structure
                    if (isset($timeRange['start_timestamp']) && isset($timeRange['end_timestamp'])) {
                        // Log the timestamp filtering being applied
                        Log::info('RetellAI API Time Range Filter', $timeRange);
                    }
                }
                
                $payload['filters'] = $filters;
            }
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/list-calls', $payload);

            if (!$response->successful()) {
                Log::error('RetellAI API Error', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                    'request_payload' => $payload
                ]);
                return [];
            }

            $data = $response->json();
            Log::info('RetellAI API Response', [
                'data_count' => is_array($data) ? count($data) : 0,
                'filters_applied' => !empty($filters)
            ]);

            return is_array($data) ? $data : [];

        } catch (\Exception $e) {
            Log::error('RetellAI - Error listing calls', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'filters' => $filters
            ]);
            return [];
        }
    }

    /**
     * Get call details
     */
    public function getCall($callId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/get-call/' . $callId);

            if (!$response->successful()) {
                Log::error('RetellAI API Error', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
                return null;
            }

            $data = $response->json();
            Log::info('RetellAI API Response', ['call_id' => $callId]);

            return $data;

        } catch (\Exception $e) {
            Log::error('RetellAI - Error getting call details', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
} 