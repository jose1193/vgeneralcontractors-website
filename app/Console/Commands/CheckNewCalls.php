<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RetellAIService;
use App\Jobs\ProcessNewCall;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckNewCalls extends Command
{
    protected $signature = 'retell:check-calls';
    protected $description = 'Check for new calls from Retell API';

    public function handle()
    {
        $retellService = new RetellAIService();
        $calls = $retellService->listCalls();
        
        Log::info('Checking for new calls', ['total_calls' => count($calls)]);
        
        $lastCheckedTimestamp = Cache::get('last_checked_call_timestamp', 0);
        $newLastCheckedTimestamp = $lastCheckedTimestamp;
        
        foreach ($calls as $call) {
            $callTimestamp = $call['start_timestamp'] ?? 0;
            
            // Si la llamada es más reciente que la última comprobada
            if ($callTimestamp > $lastCheckedTimestamp) {
                // Es una llamada nueva
                Log::info('Encontrada nueva llamada', ['call_id' => $call['call_id']]);
                ProcessNewCall::dispatch($call);
                
                // Actualizar el último timestamp revisado
                if ($callTimestamp > $newLastCheckedTimestamp) {
                    $newLastCheckedTimestamp = $callTimestamp;
                }
            }
        }
        
        // Guardar el último timestamp procesado
        Cache::put('last_checked_call_timestamp', $newLastCheckedTimestamp, now()->addDays(30));
        
        $this->info('Se comprobaron nuevas llamadas. Encontradas: ' . ($newLastCheckedTimestamp > $lastCheckedTimestamp ? 'Sí' : 'No'));
    }
}