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
    public $calls = [];
    public $startDate = '';
    public $endDate = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'start_timestamp'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
        'page' => ['except' => 1],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function mount()
    {
        $this->loadCallsFromAPI();
    }

    public function render()
    {
        $filteredCalls = $this->filterAndSortCalls();
        $paginatedCalls = $this->paginateCalls($filteredCalls);

        Log::info('Rendering calls:', [
            'total_calls' => count($this->calls),
            'filtered_calls' => $filteredCalls->count(),
            'paginated_calls' => $paginatedCalls->count(),
            'current_page' => $this->page ?? 1,
        ]);

        return view('livewire.call-records', [
            'callRecords' => $paginatedCalls
        ]);
    }

    protected function loadCallsFromAPI()
    {
        try {
            $retellService = new RetellAIService();
            $filters = [];
            
            // Apply date range filter if both dates are provided
            if (!empty($this->startDate) && !empty($this->endDate)) {
                Log::info('Applying date filter in Livewire component', [
                    'startDate' => $this->startDate,
                    'endDate' => $this->endDate
                ]);
                
                // Prepare timestamp filter (milliseconds)
                $startTimestamp = strtotime($this->startDate) * 1000;
                $endTimestamp = strtotime($this->endDate . ' 23:59:59') * 1000;
                
                $filters['time_range'] = [
                    'start_timestamp' => $startTimestamp,
                    'end_timestamp' => $endTimestamp
                ];
            }
            
            // Call API with filters
            $apiResponse = $retellService->listCalls($filters);
            
            $this->calls = collect($apiResponse);
            
            if ($this->calls->isEmpty()) {
                Log::info('No calls found in API response', ['filters' => $filters]);
            }
            
            session()->flash('message', 'Calls loaded successfully.');
        } catch (\Exception $e) {
            Log::error('Error loading calls from RetellAI', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'filters' => $filters ?? []
            ]);
            session()->flash('error', 'Error loading calls: ' . $e->getMessage());
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
        $perPage = $this->perPage;
        $items = $calls->forPage($page, $perPage);

        return new LengthAwarePaginator(
            $items,
            $calls->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    public function showCallDetails($callId)
    {
        Log::info('Showing call details', ['call_id' => $callId]);
        
        $this->selectedCall = collect($this->calls)->first(function ($call) use ($callId) {
            return isset($call['call_id']) && $call['call_id'] == $callId;
        });

        if ($this->selectedCall) {
            Log::info('Call found', ['call' => $this->selectedCall]);
            $this->showTranscript = true;
        } else {
            Log::warning('Call not found', ['call_id' => $callId]);
            session()->flash('error', 'Call record not found.');
        }
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

    /**
     * Handle date filter changes
     */
    public function applyDateFilter()
    {
        $this->resetPage();
        $this->loadCallsFromAPI();
    }
    
    /**
     * Clear date filters
     */
    public function clearDateFilter()
    {
        $this->startDate = '';
        $this->endDate = '';
        $this->resetPage();
        $this->loadCallsFromAPI();
    }
} 