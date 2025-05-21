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
     * Get list of available voices
     */
    public function listVoices()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/list-voices');

            if (!$response->successful()) {
                Log::error('RetellAI API Error', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
                return [];
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('RetellAI - Error listing voices', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    /**
     * Get a specific voice
     */
    public function getVoice($voiceId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/get-voice/' . $voiceId);

            if (!$response->successful()) {
                Log::error('RetellAI API Error', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('RetellAI - Error getting voice', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Create a knowledge base
     */
    public function createKnowledgeBase($data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($this->baseUrl . '/create-knowledge-base', $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('RetellAI - Error creating knowledge base: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * List knowledge bases
     */
    public function listKnowledgeBases()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/list-knowledge-bases');

            return $response->json();
        } catch (\Exception $e) {
            Log::error('RetellAI - Error listing knowledge bases: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Add sources to a knowledge base
     */
    public function addKnowledgeBaseSources($knowledgeBaseId, $sources)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($this->baseUrl . '/add-knowledge-base-sources/' . $knowledgeBaseId, [
                'sources' => $sources
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('RetellAI - Error adding knowledge base sources: ' . $e->getMessage());
            throw $e;
        }
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

            // Asegurarse de que tenemos un array de llamadas
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
     * Get a specific call
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

            return $response->json();
        } catch (\Exception $e) {
            Log::error('RetellAI - Error getting call', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
} 