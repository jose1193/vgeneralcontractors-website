<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Traits\CacheTraitCrud;
use Illuminate\Support\Facades\Validator;
use Throwable;
use App\Services\TransactionService;

class PortfolioCrudController extends BaseCrudController
{
    use CacheTraitCrud;

    // Constructor: inicializar propiedades necesarias
    public function __construct(TransactionService $transactionService)
    {
        parent::__construct($transactionService);
        $this->modelClass = Portfolio::class;
        $this->entityName = 'PORTFOLIO';
        $this->viewPrefix = 'portfolios-crud';
        $this->routePrefix = 'portfolios-crud';
    }

    /**
     * Display a listing of the portfolios
     */
    public function index(Request $request)
    {
        try {
            // Parámetros de búsqueda y paginación
            $this->search = $request->input('search', '');
            $this->sortField = $request->input('sort_field', 'created_at');
            $this->sortDirection = $request->input('sort_direction', 'desc');
            $this->perPage = $request->input('per_page', 10);
            $this->showDeleted = $request->input('show_deleted', 'false') === 'true';
            $page = $request->input('page', 1);

            // Query base
            $query = Portfolio::query();

            // Búsqueda
            if (!empty($this->search)) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', $searchTerm)
                      ->orWhere('description', 'like', $searchTerm);
                });
            }

            // Mostrar eliminados
            if ($this->showDeleted) {
                $query->withTrashed();
            }

            // Orden
            $query->orderBy($this->sortField, $this->sortDirection);

            // Relaciones necesarias para la tabla
            $query->with(['projectType', 'projectType.serviceCategory', 'images']);

            // Paginación
            $portfolios = $query->paginate($this->perPage, ['*'], 'page', $page);

            if ($request->ajax()) {
                // Formato compatible con el JS
                return response()->json([
                    'success' => true,
                    'data' => $portfolios->items(),
                    'current_page' => $portfolios->currentPage(),
                    'last_page' => $portfolios->lastPage(),
                    'from' => $portfolios->firstItem(),
                    'to' => $portfolios->lastItem(),
                    'total' => $portfolios->total(),
                ]);
            }

            // Vista normal (por si se accede directo)
            return view('portfolios-crud.index', [
                'entityName' => $this->entityName,
            ]);
        } catch (Throwable $e) {
            Log::error('Error loading portfolios: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading portfolios',
                ], 500);
            }
            return back()->with('error', 'Error loading portfolios');
        }
    }

    /**
     * Show the form for creating a new portfolio
     */
    public function create()
    {
        // ...
    }

    /**
     * Store a newly created portfolio
     */
    public function store(Request $request)
    {
        // ...
    }

    /**
     * Display the specified portfolio
     */
    public function show($uuid)
    {
        // ...
    }

    /**
     * Show the form for editing the specified portfolio
     */
    public function edit($uuid)
    {
        // ...
    }

    /**
     * Update the specified portfolio
     */
    public function update(Request $request, $uuid)
    {
        // ...
    }

    /**
     * Remove the specified portfolio (soft delete)
     */
    public function destroy($uuid)
    {
        // ...
    }

    /**
     * Restore a soft-deleted portfolio
     */
    public function restore($uuid)
    {
        // ...
    }

    /**
     * Check if a title already exists for real-time validation
     */
    public function checkTitleExists(Request $request)
    {
        // ...
    }

    // Métodos requeridos por BaseCrudController
    protected function getValidationRules($id = null)
    {
        // TODO: Ajustar reglas reales según el modelo Portfolio
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'service_category_id' => 'required|integer',
        ];
    }

    protected function getValidationMessages()
    {
        // TODO: Ajustar mensajes reales
        return [
            'title.required' => 'The title is required.',
            'description.required' => 'The description is required.',
            'service_category_id.required' => 'The service category is required.',
        ];
    }
} 