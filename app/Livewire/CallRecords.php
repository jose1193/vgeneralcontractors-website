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
    public $calls = []; // Cambiado a public para debugging

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'start_timestamp'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        $this->loadCallsFromAPI();
    }

    public function render()
    {
        $filteredCalls = $this->filterAndSortCalls();
        $paginatedCalls = $this->paginateCalls($filteredCalls);

        // Log para debugging
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
            $apiResponse = $retellService->listCalls();
            
            // Log de la respuesta de la API
            Log::info('API Response:', ['response' => $apiResponse]);

            // Asegurarse de que la respuesta sea un array y convertirla a collection
            if (is_array($apiResponse)) {
                $this->calls = collect($apiResponse);
                Log::info('Calls loaded successfully', ['count' => $this->calls->count()]);
            } else {
                Log::warning('API response is not an array', ['type' => gettype($apiResponse)]);
                $this->calls = collect([]);
            }

            session()->flash('message', 'Call records loaded successfully.');
        } catch (\Exception $e) {
            Log::error('Error loading calls from RetellAI', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error loading call records: ' . $e->getMessage());
            $this->calls = collect([]);
        }
    }

    protected function filterAndSortCalls()
    {
        $filtered = collect($this->calls)
            ->when($this->search, function ($collection) {
                return $collection->filter(function ($call) {
                    $searchLower = strtolower($this->search);
                    return 
                        str_contains(strtolower($call['from_number'] ?? ''), $searchLower) ||
                        str_contains(strtolower($call['to_number'] ?? ''), $searchLower) ||
                        str_contains(strtolower($call['call_analysis']['call_summary'] ?? ''), $searchLower);
                });
            });

        // Log para debugging del filtrado
        Log::info('Filtering calls:', [
            'before_filter' => count($this->calls),
            'after_filter' => $filtered->count(),
            'search_term' => $this->search
        ]);

        if ($this->sortField) {
            $filtered = $filtered->sortBy(function ($call) {
                return $call[$this->sortField] ?? '';
            }, SORT_REGULAR, $this->sortDirection === 'desc');
        }

        return $filtered;
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
        Log::info('Showing call details', ['call_id' => $callId, 'total_calls' => count($this->calls)]);
        
        $this->selectedCall = collect($this->calls)->first(function ($call) use ($callId) {
            return isset($call['call_id']) && $call['call_id'] == $callId;
        });

        if ($this->selectedCall) {
            Log::info('Call found', ['call' => $this->selectedCall]);
            $this->showTranscript = true;
        } else {
            Log::warning('Call not found', ['call_id' => $callId, 'available_ids' => collect($this->calls)->pluck('call_id')]);
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
} 