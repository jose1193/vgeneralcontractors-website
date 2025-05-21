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
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/list-calls', [
                'limit' => 100,
                'offset' => 0,
                'filters' => $filters
            ]);

            if (!$response->successful()) {
                Log::error('RetellAI API Error', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
                return [];
            }

            $data = $response->json();
            Log::info('RetellAI API Response', ['data' => $data]);

            return is_array($data) ? $data : [];

        } catch (\Exception $e) {
            Log::error('RetellAI - Error listing calls', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
            Log::info('RetellAI API Response', ['data' => $data]);

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