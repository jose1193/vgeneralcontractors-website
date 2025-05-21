<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CallRecord;
use App\Services\RetellAIService;
use Illuminate\Support\Facades\Log;

class CallRecords extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'start_timestamp';
    public $sortDirection = 'desc';
    public $selectedCall = null;
    public $showTranscript = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'start_timestamp'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        $this->syncCallsWithRetellAI();
    }

    public function render()
    {
        $query = CallRecord::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('from_number', 'like', '%' . $this->search . '%')
                        ->orWhere('to_number', 'like', '%' . $this->search . '%')
                        ->orWhere('call_summary', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.call-records', [
            'calls' => $query->paginate($this->perPage)
        ]);
    }

    public function syncCallsWithRetellAI()
    {
        try {
            $retellService = new RetellAIService();
            $calls = $retellService->listCalls();

            foreach ($calls as $call) {
                CallRecord::updateOrCreate(
                    ['call_id' => $call['call_id']],
                    [
                        'user_id' => auth()->id(),
                        'agent_id' => $call['agent_id'],
                        'from_number' => $call['from_number'],
                        'to_number' => $call['to_number'],
                        'direction' => $call['direction'],
                        'call_status' => $call['call_status'],
                        'start_timestamp' => $call['start_timestamp'],
                        'end_timestamp' => $call['end_timestamp'],
                        'duration_ms' => $call['duration_ms'],
                        'transcript' => $call['transcript'],
                        'recording_url' => $call['recording_url'],
                        'call_summary' => $call['call_analysis']['call_summary'] ?? null,
                        'user_sentiment' => $call['call_analysis']['user_sentiment'] ?? null,
                        'call_successful' => $call['call_analysis']['call_successful'] ?? false,
                        'metadata' => $call['metadata'],
                    ]
                );
            }

            session()->flash('message', 'Call records synchronized successfully.');
        } catch (\Exception $e) {
            Log::error('Error syncing calls with RetellAI: ' . $e->getMessage());
            session()->flash('error', 'Error synchronizing call records.');
        }
    }

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function showCallDetails($callId)
    {
        $this->selectedCall = CallRecord::where('call_id', $callId)->first();
        $this->showTranscript = true;
    }

    public function closeTranscript()
    {
        $this->showTranscript = false;
        $this->selectedCall = null;
    }

    public function refreshCallList()
    {
        $this->syncCallsWithRetellAI();
    }
} 