<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\RetellAIService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CallRecords extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'start_timestamp';
    public $sortDirection = 'desc';
    public $selectedCall = null;
    public $showTranscript = false;
    protected $calls = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'start_timestamp'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        $this->loadCallsFromAPI();
    }

    public function render()
    {
        $filteredCalls = $this->filterAndSortCalls();
        $paginatedCalls = $this->paginateCalls($filteredCalls);

        return view('livewire.call-records', [
            'calls' => $paginatedCalls
        ]);
    }

    protected function loadCallsFromAPI()
    {
        try {
            $retellService = new RetellAIService();
            $apiResponse = $retellService->listCalls();
            
            // Asegurarse de que la respuesta sea un array
            $this->calls = is_array($apiResponse) ? collect($apiResponse) : collect([]);
            
            if ($this->calls->isEmpty()) {
                Log::info('No call records found in API response');
            }
            
            session()->flash('message', 'Call records loaded successfully.');
        } catch (\Exception $e) {
            Log::error('Error loading calls from RetellAI: ' . $e->getMessage());
            session()->flash('error', 'Error loading call records: ' . $e->getMessage());
            $this->calls = collect([]);
        }
    }

    protected function filterAndSortCalls()
    {
        return collect($this->calls)
            ->when($this->search, function ($collection) {
                return $collection->filter(function ($call) {
                    $searchLower = strtolower($this->search);
                    return 
                        str_contains(strtolower($call['from_number'] ?? ''), $searchLower) ||
                        str_contains(strtolower($call['to_number'] ?? ''), $searchLower) ||
                        str_contains(strtolower($call['call_analysis']['call_summary'] ?? ''), $searchLower);
                });
            })
            ->when($this->sortField, function ($collection) {
                return $collection->sortBy($this->sortField, SORT_REGULAR, $this->sortDirection === 'desc');
            });
    }

    protected function paginateCalls($calls)
    {
        $page = $this->page ?? 1;
        $items = $calls->forPage($page, $this->perPage);
        
        return new LengthAwarePaginator(
            $items,
            $calls->count(),
            $this->perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );
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
        $this->selectedCall = collect($this->calls)->firstWhere('call_id', $callId);
        if (!$this->selectedCall) {
            Log::warning('Call record not found for ID: ' . $callId);
            session()->flash('error', 'Call record not found.');
            return;
        }
        $this->showTranscript = true;
    }

    public function closeTranscript()
    {
        $this->showTranscript = false;
        $this->selectedCall = null;
    }

    public function refreshCallList()
    {
        $this->loadCallsFromAPI();
    }
} 